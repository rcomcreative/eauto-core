<?php

namespace Eauto\Core\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Laravel\Scout\Searchable;

class Make extends Model
{
    use HasFactory, Searchable;

    protected  $fillable = ['name','battleground','sales_forecast','manufacturer_id','division_id','id'];

    protected $casts = [
        'battleground' => 'boolean',
        'sales_forecast' => 'boolean',
    ];

    public function manufacturer() {
        return $this->belongsTo(Manufacturer::class);
    }

    public function divisions() {
    return $this->belongsTo(Division::class);
    }

    public function brandLogo() {
        return $this->hasOne(BrandLogo::class);
    }

    public function vehicles() {
        return $this->hasMany(Vehicle::class)
            ->where('Active_flag', 1)
            ->where('SalesForecast_Flag',1)
            ->with('segment')
            ->orderBy('Truck_flag','ASC')
            ->orderBy( 'name', 'ASC');
    }

    public function carVehicles()
    {
        return $this->hasMany(Vehicle::class)
            ->where('Active_flag', 1)
            ->where('SalesForecast_Flag', 1)
            ->where('Truck_flag', 0); // 0 = Car
    }

    public function truckVehicles()
    {
        return $this->hasMany(Vehicle::class)
            ->where('Active_flag', 1)
            ->where('SalesForecast_Flag', 1)
            ->where('Truck_flag', 1); // 1 = Truck
    }

    public function vehicleyears($id,$flag,$minyear,$maxyear) {
        $minmax = $maxyear-$minyear;
        $minmax = $minmax+1;
        $make = Make::where('id',$id)
            ->where('battleground',1)
            ->where('sales_forecast',1)
            ->with('vehicles')->first();
        $vs = $make->vehicles;
        $counted = count($vs);
        $vehiclecar = Array();
        $makecarsales = Array();
        for($s=0;$s<$counted;$s++) {
            $vehicle = $vs[ $s ];
            $vid = $vehicle->id;
            $sales = Salesforecast::where('vehicle_id',$vid)
                ->whereBetween('sales_year',[$minyear,$maxyear])
                ->selectRaw('SUM(sales_value) as total, sales_year' )
                ->groupBy('sales_year')
                ->orderBy('sales_year', 'ASC')
                ->get();
            if(sizeof($sales)) {
                $sold = $sales->where('total','0.00');
                $sCount = count($sold);
                if($sCount < $minmax) {
                    if ($vehicle->Truck_flag == $flag) {
                        $v = [];
                        $theSales = $sales->pluck('total');
                        $theCount = count($theSales);
                        if($theCount == $minmax) {
                            $v['id'] = $vehicle->id;
                            $v['name'] = $vehicle->name;
                            $v['segment'] = $vehicle->segment->name;
                            $v['sales'] = $theSales;
                            $v['years'] = $sales->pluck('sales_year');
                            $v['minmax'] = $minmax;
                            $v['sold'] = $sold;
                            array_push($vehiclecar, $v);
                        }
                    }
                }
            }
        }
        return $vehiclecar;
    }

    public function makesales($id,$flag,$minyear,$maxyear) {
        $minmax = $maxyear-$minyear;
        $minmax = $minmax+1;
        $sales = DB::table('salesforecasts')
            ->join('vehicles', 'salesforecasts.vehicle_id', '=', 'vehicles.id')
            ->where('vehicles.make_id','=',$id)
            ->where('vehicles.Truck_flag','=',$flag)
            ->whereBetween('sales_year', [$minyear, $maxyear])
            ->select('sales_year',DB::raw('SUM(sales_value) as total'))
            ->groupBy('sales_year')
            ->orderBy('sales_year','ASC')
            ->get();
        $makesales = $sales->pluck('total');
        return $makesales;
    }

    public function vehiclesCount() {
        $vs = $this->vehicles;
        $count = count($vs);
        return $count;
    }

    public function storedCalculations($minyear, $maxyear) {
        $stored716calculations = $this->getStored716Calculations($minyear, $maxyear);
        $stored715calculations = $this->getStored715Calculations($minyear, $maxyear);
        $count = count($stored715calculations);
        $cal715 = $stored715calculations->pluck('total');
        $cal716 = $stored716calculations->pluck('total');
        /*make the total stored calculations using $cal175 and $cal176*/
        $storedCalculation = Array();
        for($s=0;$s<$count;$s++) {
            $total = $cal715[$s] + $cal716[$s];
            $total = round( $total, 3);
            if(!str_contains($total,'.')) {
                $total = $total.'.0';
            }
            $storedCalculation[$s] = $total;
        }
        $salescartruck = $this->getSales($minyear, $maxyear);
        $sales = $salescartruck->pluck('total');
        $percentagesales = $salescartruck->pluck('total');
        $salescar = $this->getCarsales($minyear, $maxyear);
        $carsales = $salescar->pluck('total');
        $percentagecars = $salescar->pluck('total');
        $salestruck = $this->getTrucksales($minyear, $maxyear);
        $trucksales = $salestruck->pluck('total');
        $percentagetrucks = $salestruck->pluck('total');
        /*build percentages*/
        $count = count($percentagecars);
        $this->getTransform($percentagecars, $count, $cal715);
        $this->getTransform($percentagetrucks,$count, $cal716);
        $this->getTransform($percentagesales,$count,$storedCalculation);
        $this->cal715 = $cal715;
        $this->cal716 = $cal716;
        $this->storedCalculation = $storedCalculation;
        $this->sales = $sales;
        $this->carsales = $carsales;
        $this->trucksales = $trucksales;
        $this->percentagecars = $percentagecars;
        $this->percentagetrucks = $percentagetrucks;
        $this->percentagesales = $percentagesales;
        return $this;
    }

    /**
     * @param \Illuminate\Support\Collection $percentagecars
     * @param int                            $count
     * @param                                $cal715
     *
     * @return void
     */
    public function getTransform(\Illuminate\Support\Collection $percentagecars, int $count, $cal715): void
    {
        $percentagecars->transform(function (string $item, int $key) use ($count, $cal715) {
            for ($s = 0; $s < $count; $s ++) {
                $items = round(($item * 100) / $cal715[ $s ], 3);
                $items = substr($items, 0, - 1);
                $items = $items . '%';
                if (strpos($items, ".%")) {
                    $items = substr($items, 0, - 2);
                    $items = $items . '.0%';
                }

                return $items;
            }
        });
    }

    /**
     * @param $minyear
     * @param $maxyear
     *
     * @return \Illuminate\Support\Collection
     */
    public function getTrucksales($minyear, $maxyear): \Illuminate\Support\Collection
    {
        $trucksales = DB::table('salesforecasts')
            ->join('vehicles', 'vehicles.id', '=', 'salesforecasts.vehicle_id')
            ->whereBetween('sales_year', [$minyear, $maxyear])
            ->where('vehicles.make_id', $this->id)
            ->where('vehicles.SalesForecast_flag', 1)
            ->where('vehicles.Active_flag', 1)
            ->where('salesforecasts.category_item_id', 716)
            ->selectRaw('SUM(salesforecasts.sales_value) as total, salesforecasts.sales_year')
            ->groupBy('salesforecasts.sales_year')
            ->orderBy('salesforecasts.sales_year', 'ASC')
            ->get();

        return $trucksales;
    }

    /**
     * @param $minyear
     * @param $maxyear
     *
     * @return \Illuminate\Support\Collection
     */
    public function getCarsales($minyear, $maxyear): \Illuminate\Support\Collection
    {
        $carsales = DB::table('salesforecasts')
            ->join('vehicles', 'vehicles.id', '=', 'salesforecasts.vehicle_id')
            ->whereBetween('sales_year', [$minyear, $maxyear])
            ->where('vehicles.make_id', $this->id)
            ->where('vehicles.SalesForecast_flag', 1)
            ->where('vehicles.Active_flag', 1)
            ->where('salesforecasts.category_item_id', 715)
            ->selectRaw('SUM(salesforecasts.sales_value) as total, salesforecasts.sales_year')
            ->groupBy('salesforecasts.sales_year')
            ->orderBy('salesforecasts.sales_year', 'ASC')
            ->get();

        return $carsales;
    }

    /**
     * @param $minyear
     * @param $maxyear
     *
     * @return \Illuminate\Support\Collection
     */
    public function getSales($minyear, $maxyear): \Illuminate\Support\Collection
    {
        $sales = DB::table('salesforecasts')
            ->join('vehicles', 'vehicles.id', '=', 'salesforecasts.vehicle_id')
            ->whereBetween('sales_year', [$minyear, $maxyear])
            ->where('vehicles.make_id', $this->id)
            ->where('vehicles.SalesForecast_flag', 1)
            ->where('vehicles.Active_flag', 1)
            ->selectRaw('SUM(salesforecasts.sales_value) as total, salesforecasts.sales_year')
            ->groupBy('salesforecasts.sales_year')
            ->orderBy('salesforecasts.sales_year', 'ASC')
            ->get();

        return $sales;
    }

    /**
     * @return mixed
     */
    public function getStored715Calculations($minyear, $maxyear)
    {
        $stored715calculations = StoredCalculation::whereBetween('sales_year', [$minyear, $maxyear])
            ->where('category_item_id', 715)
            ->where('sfc_id', 1)
            ->selectRaw('SUM(total) as total, sales_year')
            ->groupBy('sales_year')
            ->orderBy('sales_year', 'ASC')
            ->get();

        return $stored715calculations;
    }

    /**
     * @return mixed
     */
    public function getStored716Calculations($minyear, $maxyear)
    {
        $stored716calculations = StoredCalculation::whereBetween('sales_year', [$minyear, $maxyear])
            ->where('category_item_id', 716)
            ->where('sfc_id', 1)
            ->selectRaw('SUM(total) as total, sales_year')
            ->groupBy('sales_year')
            ->orderBy('sales_year', 'ASC')
            ->get();

        return $stored716calculations;
    }


}
