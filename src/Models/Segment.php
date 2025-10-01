<?php

namespace Eauto\Core\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Laravel\Scout\Searchable;

class Segment extends Model
{
    use HasFactory, Searchable;

    protected  $fillable = ['name','Truck_flag' ,'battleground','sales_forecast','sales_forecast_active','id'];

    protected $casts = [
        'truck_flag' => 'boolean',
        'battleground' => 'boolean',
        'sales_forecast' => 'boolean',
        'sales_forecast_active' => 'boolean',
    ];

    public function vehicle() {
        return $this->hasMany(Vehicle::class)
            ->orderBy('make_id','ASC');
    }

    public function segmentPhotos($id) {
        $vehicles = Vehicle::where('segment_id',$id)
            ->where('make_id', '!=', 0)
            ->orderBy('make_id','ASC')
            ->orderBy('id','ASC')
            ->get();
        return $vehicles;
    }

    public function segmentVehicles($id) {
            $segment = Segment::where('id',$id)->first();
            $vehicles = $segment->segmentPhotos($id);
//            dd($vehicles);
            $count = count($vehicles);

            $segments = Array();
            for($s=0;$s<$count;$s++) {
                $seg = [];
                $vehicle_id = $vehicles[$s]['id'];
                $seg['vehicle_id'] = $vehicle_id;
                $make_id = $vehicles[$s]['make_id'];
                $seg['make_id'] = $make_id;
                $seg['name'] = $vehicles[$s]['name'];
                $main = $vehicles[$s]->mainVehiclePhoto;
                if (isset($main)) {
                    $main_photo = $main->large_file_name;
                    $path = "/public/upload_photos/" . $make_id . "/" . $vehicle_id . "/thumb/" . $main_photo;
                    $url = Storage::disk('spaces')->url($path);
                    $seg['main_photo'] = $url;
//                    $main_photo = $main['large_file_name'];
//                    $seg['main_photo'] = "/images/upload_photo/" . $make_id . "/" . $vehicle_id. "/" . $main_photo;
                } else {
//                    $seg['main_photo'] = "/images/brandlogo/eauto-white.png";
                    $seg['main_photo'] = "/images/eauto_flag-01.jpg";
                }
                array_push($segments, $seg);
            }

        return $segments;

    }

    public function allVehicles()
    {
        $theSegments = Segment::where('battleground', 1)
            ->with('vehicle')
            ->orderBy('name', 'ASC')
            ->get();

        $count = count($theSegments);
        $segments = Array();
        for($s=0;$s<$count;$s++) {
            $seg = [];
            $vehicles = $theSegments[ $s ]->vehicle;
            $vcount = count($theSegments[ $s ]->vehicle);
            if ($vcount > 0) {
                $seg['segment'] = $theSegments[ $s ]['name'];
                $segment = $theSegments[$s];
                $sid = $segment['id'];
                $seg['segment_id'] = $sid;
                $vehiclecount = count($vehicles);
                $seg['count'] = $vehiclecount;
                $main = $this->segmentPhoto($sid);
                if ($main->isEmpty()) {
                    $seg['vehicle_count'] = $vehiclecount;
                    $make_id = '';
                    $seg['make_id'] = $make_id;
                    $vid = 0;
                    $seg['vehicle_id'] = $vid;
                    $main_photo = "/images/brandlogo/eauto-white.png";
                    $seg['main_photo'] = $main_photo;
                } else {
                    $main_vehicle_photo = $main->first();
                    $vid = $main_vehicle_photo->vehicle_id;
                    $vehicle = Vehicle::where('id', $vid)->first();
                    $make_id = $vehicle->make_id;
                    $vehicle_id = $vehicle->id;
                    $main_photo = $main_vehicle_photo->large_file_name;
                    $path = "/public/upload_photos/" . $make_id . "/" . $vehicle_id . "/thumb/" . $main_photo;
                    $url = Storage::disk('spaces')->url($path);
                    $seg['vehicle_count'] = $vehiclecount;
                    $seg['make_id'] = $make_id;
                    $seg['vehicle_id'] = $vid;
                    $seg['main_photo'] = $url;
                }
                array_push($segments, $seg);
            }

        }
        return $segments;

    }

    public function segmentPhoto($id) {
        $main_photos = Array();
        $vehicles = Vehicle::where('segment_id',$id)->with('mainVehiclePhoto')->get();
        $main = $vehicles->pluck('mainVehiclePhoto');
        $filtered = $main->filter();
        return $filtered;
    }


}
