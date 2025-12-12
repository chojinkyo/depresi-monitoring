<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Diary;
use App\Models\Siswa;
use Illuminate\Http\Request;
use App\Models\TahunAkademik;
use Illuminate\Support\Carbon;

class DiaryController extends Controller
{
    public function index(Request $request)
    {
        $academicYear=$request->has('year') ? $request->input('year') : TahunAkademik::orderByRaw('(3 - current - status) ASC')->orderBy('nama_tahun', 'desc')->first()?->id;

        $students=Siswa::query()
        ->with('activeClass', 'user')
        ->select(['id', 'nisn', 'nama_lengkap', 'id_user'])
        ->paginate(10);
        $studentIds=$students->pluck('id')->toArray();
        

        $mentalHealthData=Diary::getMentalHealthData($academicYear, $studentIds);
        // dd($mentalHealthData);
        $students=$students->map(function($student) use($mentalHealthData) {
            $details = collect($mentalHealthData->get($student->id));
            

            $dpMeter = $details->reduce(function($acc, $row) {
                $swafoto_pred=strtolower($row->swafoto_pred);
                $catatan_pred=strtolower($row->catatan_pred);
                $bool=($catatan_pred==='terindikasi depresi' && !in_array($swafoto_pred, ['happy', 'surprise']));
                return $acc + (int) $bool;
            }, 0);
            
            
            $percentage=0;
            $totals=$details->count();
            
            $day1="";
            $day2="";
            if($totals > 0)
            {
                $percentage=($dpMeter / $totals) * 100;
                $day1=$details->first()?->waktu;
                $day2=$details->last()?->waktu;

                $day1=Carbon::parse($day1)->format('j F Y');
                $day2=Carbon::parse($day2)->format('j F Y');
            }

            $results=collect([
                'days'=>$totals,
                'depression_rate'=>$percentage,
                'time_range'=>"$day1 - $day2"
            ]);

            $student->mental_health=collect([
                'result'=>$results,
                'detail'=>$details
            ]);
            return $student;
        });
        // dd($students);

        
        return view('admin.diary.index', compact('students'));
    }
}
