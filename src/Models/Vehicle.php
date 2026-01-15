<?php

namespace Eauto\Core\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Laravel\Scout\Searchable;

class Vehicle extends Model
{
    use HasFactory, Searchable;

    protected  $fillable = ['name','make_id','cbg_id','segment_id','Truck_flag' ,'Delete_flag','Active_flag','SalesForecast_flag','id'];

    protected $attributes = [
        'cbg_id' => 1,
    ];

    /*casts*/
    // app/Models/Vehicle.php
    protected $casts = [
        'Truck_flag'          => 'bool',
        'Delete_flag'         => 'bool',
        'Active_flag'         => 'bool',
        'SalesForecast_flag'  => 'bool',
    ];

    /**
     * @param $q
     * @return mixed
     * reusable query scopes
     */
    public function scopeActive($q)          { return $q->where('Active_flag', true); }
    public function scopeForecastable($q)    { return $q->where('SalesForecast_flag', true); }
    public function scopeCars($q)            { return $q->where('Truck_flag', false); }
    public function scopeTrucks($q)          { return $q->where('Truck_flag', true); }
    public function scopeForMake($q, $id)    { return $q->where('make_id', $id); }

    /* Make belone to*/
    public function make() {
        return $this->belongsTo(Make::class);
    }

    public function scopeCbgOne($query)
    {
        return $query->where('cbg_id', 1);
    }

//    public function customSegments()
//    {
//        return $this->belongsToMany(
//            \Eauto\Core\Models\DepartmentCustomSegment::class,
//            'department_custom_segment_vehicles',
//            'vehicle_id',
//            'department_custom_segment_id'
//        )->withTimestamps();
//    }

    public function customSegments()
    {
        return $this->belongsToMany(
            \Eauto\Core\Models\DepartmentCustomSegment::class,
            'department_custom_segment_vehicles',
            'vehicle_id',
            'department_custom_segment_id'
        )->withTimestamps();
    }

    /**
     * Filament's AttachAction may look for an inverse relationship named
     * `departmentCustomSegments()` on the related model (Vehicle) when managing
     * DepartmentCustomSegment <-> Vehicle. Keep `customSegments()` as the
     * canonical relationship, but provide this alias for compatibility.
     */
    public function departmentCustomSegments()
    {
        return $this->customSegments();
    }

    /* Segent belongsTo*/
    public function segment() {
        return $this->belongsTo(Segment::class);
    }

    public function battleground()
    {
        return $this->hasOne(VehicleBattleground::class);
    }

    public function battlegroundCycles()
    {
        return $this->hasMany(VehicleBattlegroundCycle::class)
            ->orderBy('line_year', 'DESC');
    }

    /*3 changes hasOne*/
    public function threeChanges() {
        return $this->hasOne( VehicleBattleground::class);
    }

    /*keypoint hasOne*/
    public function keypoint() {
        return $this->hasOne(VehicleKeypoint::class)
            ->where('live','1');
    }

    /*main photo hasOne*/
    public function mainVehiclePhoto() {
        return $this->hasOne(VehiclePhoto::class)
            ->where('active',1)
            ->where('main',1);
    }

    /* suspension hasOne*/
    public function vehicleSuspension() {
        return $this->hasOne(VehicleSuspension::class);
    }


    /* futureInten hasOne*/
    public function futureIntel() {
        return $this->hasOne(VehicleFutureIntel::class);
    }


    /* cgequipupdate hasOne*/
    public function cgequipupdates() {
        return $this->hasOne(VehicleCGEquipUpdate::class);
    }


    /* cglaunchinfo hasOne*/
    public function cglaunchinfo() {
        return $this->hasOne(VehicleCGLaunchInfo::class);
    }

    /* prior hasOne*/
    public function priorgenerationinfo() {
        return $this->hasOne(VehiclePriorGenerationInfo::class);
    }

    /* vehicleconfig hasOne*/
    public function vehicleconfigpowertrainprofile() {
        return $this->hasOne(VehicleConfigPowerTrainProfile::class);
    }

    /* autopacifictake hasOne*/
    public function autopacificstake() {
        return $this->hasOne(VehicleAutoPacificsTake::class);
    }

/*press releases hasMany*/
    public function pdfText() {
        return $this->hasMany(Content::class)
            ->where('activeFlag',1)
            ->where('deleteFlag', 0)
            ->orderBy('publishDate', 'DESC');
    }
    public function pdfList() {
        return $this->hasMany( VehiclePressRelease::class)
            ->orderBy('sortColumn', 'DESC');
    }


    /*cycletext hasMany*/
    public function cycleText() {
        return $this->hasMany(VehicleBattlegroundCycle::class)
            ->orderBy('line_year', 'DESC');
    }


    /*dimensions hasMany*/
    public function dimensions() {
        return $this->hasMany(VehicleDimension::class)
            ->where('delete_flag',0)
            ->orderBy('id', 'ASC');
    }

    /*engines hasMany*/
    public function engines() {
        return $this->hasMany(Vehicle_engine::class)
            ->where('delete_flag',0)
            ->orderBy('order');
    }

    /*priceranges hasMany*/
    public function priceranges() {
        return $this->hasMany(VehiclePriceRange::class)
            ->where('delete_flag',0)
            ->orderBy('time_stamp');
    }

    /*saleslaunch hasMany*/
    public function salesLaunchs() {
        return $this->hasMany(VehicleSalesLaunch::class)
            ->where('delete_flag',0)
            ->orderBy('time_stamp');
    }

    /*productionstart hasMany*/
    public function productionStarts() {
        return $this->hasMany(VehicleStartOfProduction::class)
            ->where('delete_flag',0)
            ->orderBy('time_stamp');
    }

    /*curbrange hasMany*/
    public function curbweightranges() {
        return $this->hasMany(Vehicle_weight_range::class)
            ->where('delete_flag',0)
            ->orderBy('order');
    }

    /*cycleText hasMany start and end year*/
    public function vehicleCycleText($yearStart, $yearEnd) {
        return $this->cycleText()
            ->where('line_year', '>=', $yearStart)
            ->where('line_year', '<=', $yearEnd)
            ->orderBy( 'line_year', 'DESC');
    }

    /*photos hasMany*/
    public function vehiclePhotos() {
        return $this->hasMany(VehiclePhoto::class)
            ->where('active',1)
            ->orderBy('main','DESC')
            ->orderBy( 'model_year', 'DESC');
    }

    /*tiresizes hasMany*/
    public function tiresizes() {
        return $this->hasMany(VehicleTireSize::class)
            ->where('delete_flag',0)
            ->orderBy('order');
    }

    /*alternatives belongsToMany NOTUSED*/
    public function alternatives() {
        return $this->belongsToMany(Alternative::class)->withTimestamps();
    }

    /*drives belongsToMany*/
    public function drives() {
        return $this->belongsToMany(Drive::class)->withTimestamps();
    }

    /*seats belongsToMany*/
    public function seats() {
        return $this->belongsToMany(Seat::class)->withTimestamps();
    }

    /*bodystyles belongsToMany NOTUSED*/
    public function bodystyles() {
        return $this->belongsToMany(Bodystyle::class)
            ->orderBy('bodystyle_id','ASC');
    }



    /*tags belongsToMany NOTUSED*/
    public function tags() {
        return $this->belongsToMany(Tag::class)->withTimestamps();
    }

    /*transmission belongsToMany */
    public function transmissions() {
        return $this->belongsToMany(Transmission::class)->withTimestamps();
    }

    // If you still want a “list of all segments” helper,
    // better as a static or a repository, not an Eloquent relationship:
    public static function allSegments()
    {
        return Segment::select('id', 'name')
            ->orderBy('Truck_flag', 'ASC')
            ->orderBy('name', 'ASC')
            ->get();
    }

    /*vphotos function*/
    public function vphotos($id) {
        $photos = VehiclePhoto::where('vehicle_id',$id)
            ->where( 'active',1)
            ->select('id','large_file_name','photo_caption','photo_credit','model_year')
            ->orderBy('main','DESC')
            ->orderBy( 'model_year', 'DESC')
            ->get();

        return $photos;
    }

    /*salesforecast hasMany*/
    public function salesforecast() {
        return $this->hasMany(Salesforecast::class)
            ->orderBy('sales_year', 'DESC')
            ->orderBy('bodystyle_id', 'ASC');
    }

    /*sales function*/
    public function sales($id) {
        $sales = Salesforecast::selectRaw('SUM(sales_value) as total, sales_year, bodystyle_id' )
            ->where('vehicle_id', $id)
            ->groupBy('sales_year')
            ->groupBy('bodystyle_id')
            ->orderBy('bodystyle_id', 'ASC')
            ->orderBy('sales_year','ASC')
            ->get();
        return $sales;
    }

    /*return all bodystyles for vehicle during years*/
    public function vehicle_bodystyle_sales($id,$minyear, $maxyear ) {
        $sales = Salesforecast::whereBetween('sales_year',[$minyear,$maxyear])
            ->where('vehicle_id', $id)
            ->selectRaw('SUM(sales_value) as total, sales_year, bodystyle_id' )
            ->groupBy('sales_year')
            ->groupBy('bodystyle_id')
            ->orderBy('bodystyle_id', 'ASC')
            ->orderBy('sales_year','ASC')
            ->get();
        return $sales;
    }

    /*return sales for vehicle single bodystyle*/
    public function sales_vehicle($id,$bid,$minyear, $maxyear) {
        $sales = Salesforecast::whereBetween('sales_year',[$minyear,$maxyear])
            ->where('vehicle_id', $id)
            ->where('bodystyle_id', $bid)
            ->selectRaw('SUM(sales_value) as total, sales_year, bodystyle_id' )
            ->groupBy('sales_year')
            ->groupBy('bodystyle_id')
            ->orderBy('bodystyle_id', 'ASC')
            ->orderBy('sales_year','ASC')
            ->get();
        return $sales;
    }

    /*return sales calc for either 715 or 716*/
    public function sales_calc_715_716($calID,$min, $max) {
        $stored716calculations = StoredCalculation::whereBetween('sales_year', [$min, $max])
            ->where('category_item_id',$calID)
            ->where('sfc_id', 1)
            ->selectRaw('SUM(total) as total, sales_year')
            ->groupBy('sales_year')
            ->orderBy('sales_year', 'ASC')
            ->get();
        return $stored716calculations;
    }

    /*return all sales for vehicle during years*/
    public function vehicle_all_sales($vid, $min,$max) {
        $sales = Salesforecast::whereBetween('sales_year',[$min,$max])
            ->selectRaw('SUM(sales_value) as total, sales_year')
            ->where('vehicle_id', $vid)
            ->groupBy('sales_year')
            ->orderBy('sales_year','ASC')
            ->get();
        return $sales;
    }

    /*get percentages between make and vehicle*/
//    public function vehicle_percentage($vehicles,$count,$makes) {
//        $percentages = $this->getTransform($vehicles, $count, $makes);
//        dd($percentages);
//        return $this;
//    }

    /*percentage transformation*/
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
                $items =  $items == '%' ? '0.%' : $items;
                return $items;
            }
        });
    }

    /*vehiclesales function*/
    public function vehiclesales($id) {
        $sales = Salesforecast::selectRaw('SUM(sales_value) as total, sales_year' )
            ->where('vehicle_id', $id)
            ->groupBy('sales_year')
            ->orderBy('sales_year','ASC')
            ->get();
        return $sales;
    }

    /*makesales function*/
    public function makesales($make_id) {
        $sales = DB::table('salesforecasts')
            ->join('vehicles', 'vehicles.id', '=', 'salesforecasts.vehicle_id')
            ->where('salesforecasts.sales_year', '>=' , 1987)
            ->where('vehicles.make_id', $make_id)
            ->where( 'vehicles.SalesForecast_flag',1)
            ->where('vehicles.Active_flag', 1)
            ->selectRaw('SUM(salesforecasts.sales_value) as total, salesforecasts.sales_year' )
            ->groupBy('salesforecasts.sales_year')
            ->orderBy('salesforecasts.sales_year','ASC')
            ->get();
        return $sales;
    }

    /*makecarsales*/
    public function makecarsales($make_id) {
        $sales = DB::table('salesforecasts')
            ->join('vehicles', 'vehicles.id', '=', 'salesforecasts.vehicle_id')
            ->where('salesforecasts.sales_year', '>=' , 1987)
            ->where('vehicles.make_id', $make_id)
            ->where('Truck_flag', 1)
            ->where( 'vehicles.SalesForecast_flag',1)
            ->where('vehicles.Active_flag', 1)
            ->selectRaw('SUM(salesforecasts.sales_value) as total, salesforecasts.sales_year' )
            ->groupBy('salesforecasts.sales_year')
            ->orderBy('salesforecasts.sales_year','ASC')
            ->get();
        return $sales;
    }

    /*make truck sales*/
    public function maketrucksales($make_id) {
        $sales = DB::table('salesforecasts')
            ->join('vehicles', 'vehicles.id', '=', 'salesforecasts.vehicle_id')
            ->where('salesforecasts.sales_year', '>=' , 1987)
            ->where('vehicles.make_id', $make_id)
            ->where('Truck_flag', 0)
            ->where( 'vehicles.SalesForecast_flag',1)
            ->where('vehicles.Active_flag', 1)
            ->selectRaw('SUM(salesforecasts.sales_value) as total, salesforecasts.sales_year' )
            ->groupBy('salesforecasts.sales_year')
            ->orderBy('salesforecasts.sales_year','ASC')
            ->get();
        return $sales;

    }

    /*storedcalculations function*/
    public function storedcalculations() {
        $storedcalculations = StoredCalculation::distinct('sales_year')
            ->selectRaw('SUM(total) as total, sales_year' )
            ->where('category_item_id', 715)
            ->orWhere('category_item_id',716)
            ->where('sfc_id', 1)
            ->groupBy('sales_year')
            ->orderBy('sales_year', 'ASC')
            ->get();
        return $storedcalculations;
    }

/*stored calculation for 715 or 716 with min and max years*/
public function stored_calculations($cal,$min,$max) {
    $storedcalculations = StoredCalculation::whereBetween('sales_year',[$min,$max])
        ->distinct('sales_year')
        ->selectRaw('SUM(total) as total, sales_year' )
        ->where('category_item_id', $cal)
        ->where('sfc_id', 1)
        ->groupBy('sales_year')
        ->orderBy('sales_year', 'ASC')
        ->get();
    return $storedcalculations;
}
    public function stored715calculations() {
        $storedcalculations = StoredCalculation::distinct('sales_year')
            ->selectRaw('SUM(total) as total, sales_year' )
            ->where('category_item_id', 715)
            ->where('sfc_id', 1)
            ->groupBy('sales_year')
            ->orderBy('sales_year', 'ASC')
            ->get();
        return $storedcalculations;
    }

    public function stored716calculations() {
        $storedcalculations = StoredCalculation::distinct('sales_year')
            ->selectRaw('SUM(total) as total, sales_year' )
            ->where('category_item_id', 716)
            ->where('sfc_id', 1)
            ->groupBy('sales_year')
            ->orderBy('sales_year', 'ASC')
            ->get();
        return $storedcalculations;
    }

    /*ubodystyle function used for a short list of bodystyles*/
    public function ubodystyle($id) {
        $bodyStyles = Salesforecast::distinct()
            ->where('vehicle_id', $id)
            ->orderBy('bodystyle_id','DESC')
            ->get('bodystyle_id');
        $styles = Bodystyle::get();
        $count = count($styles);
        $scount = count($bodyStyles);
        $bStyle = Array();
        for($p=0;$p<$scount;$p++){
            $b = $bodyStyles[$p]['bodystyle_id'];
            for($s=0;$s<$count;$s++){
                $bs = $styles[$s]['id'];
                if($b  ==  $bs) {
                    $bStyle[$s]['bodystyle_id'] = $b;
                    $bStyle[$s]['name'] = $styles[$s]['name'];
                    $bStyle[$s]['abbrev'] = $styles[$s]['abbrev'];
                }
            }
        }
        $bStyle = array_values($bStyle);
        return $bStyle;
    }

    /*salesnobody not used*/
    public function salesnobody($id) {
        $sales = Salesforecast::selectRaw('SUM(sales_value) as total, sales_year' )
            ->where('vehicle_id', $id)
            ->groupBy('sales_year')
            ->orderBy('sales_year','ASC')
            ->get();
        return $sales;
    }

    /*uniqueBodyStyles function
     used for full list for salesforecast left column*/
    public function uniqueBodyStyles($id) {
        $bodyStyles = Salesforecast::distinct()
            ->where('vehicle_id', $id)
            ->orderBy('bodystyle_id','DESC')
            ->get('bodystyle_id');
        $vehicle = Vehicle::where('id',$id)->get();
        $vName = $vehicle->pluck('name');
        $vehicleName = $vName[0];
        $mid = $vehicle->pluck('make_id');
        $makeID = $mid[0];
        $make = Make::where('id',$makeID)->first();
        $make_name = $make->name;
        $styles = Bodystyle::get();
        $count = count($styles);
        $scount = count($bodyStyles);
        $bStyle = Array();
        $bStyle[0]['bodystyle_id'] = 0;
        $bStyle[0]['name'] =  'Calendar Years';
        for($p=0;$p<$scount;$p++){
            $b = $bodyStyles[$p]['bodystyle_id'];
            for($s=0;$s<$count;$s++){
                $bs = $styles[$s]['id'];
                if($b  ==  $bs) {
                    $bStyle[$s]['bodystyle_id'] = $b;
                    $bStyle[$s]['name'] = $styles[$s]['name'];
                }
            }
        }
        $bStyle = array_values($bStyle);
        $scount = $scount+1;
        $bStyle[$scount]['bodystyle_id'] = $scount;
        $bStyle[$scount]['name'] =  $vehicleName.' Totals';
        $scount = $scount+1;
        $bStyle[$scount]['bodystyle_id'] = $scount;
        $bStyle[$scount]['name'] =  'Share of '.$make_name.' Totals';
        $scount = $scount+1;
        $bStyle[$scount]['bodystyle_id'] = $scount;
        $bStyle[$scount]['name'] =  $make_name.' Totals';
        $scount = $scount+1;
        $bStyle[$scount]['bodystyle_id'] = $scount;
        $bStyle[$scount]['name'] =  'Passenger Car Totals';
        $scount = $scount+1;
        $bStyle[$scount]['bodystyle_id'] = $scount;
        $bStyle[$scount]['name'] =  'Light Truck Totals';
        $scount = $scount+1;
        $bStyle[$scount]['bodystyle_id'] = $scount;
        $bStyle[$scount]['name'] =  'Light Vehicle Totals';
        return $bStyle;
    }


    /* NOT USED*/
    /*cycletextsection function*/
    private function cycleTextSection($vehicle) {
        $theList = array();
        $list['id'] = $vehicle->id;
        $list['section1'] = $vehicle->cycleText()
            ->where('line_year', '>=', '1987')
            ->where('line_year', '<=', '1995')
            ->orderBy( 'line_year', 'DESC');
        $list['secion2'] = $vehicle->cycleText()
            ->where('line_year', '>=', '1996')
            ->where('line_year', '<=', '2004')
            ->orderBy( 'line_year', 'DESC');
        $list['secion3'] = $vehicle->cycleText()
            ->where('line_year', '>=', '2005')
            ->where('line_year', '<=', '2013')
            ->orderBy( 'line_year', 'DESC');
        $list['secion4'] = $vehicle->cycleText()
            ->where('line_year', '>=', '2014')
            ->where('line_year', '<=', '2022')
            ->orderBy( 'line_year', 'DESC');
        $list['secion5'] = $vehicle->cycleText()
            ->where('line_year', '>=', '2023')
            ->where('line_year', '<=', '2031')
            ->orderBy( 'line_year', 'DESC');
        $theList = $list;
        return $theList;
    }

    /*cycletext function*/
    public function vehiclesCycleTextList($theVehicles) {
        $theCycleText = array();
        $index = 0;
        foreach ($theVehicles as $vehicle) {
            $list = $this->cycleTextSection($vehicle);
            $theCycleText[$index] = $list;
            $index++;
        }
        return $theCycleText;
    }



}
