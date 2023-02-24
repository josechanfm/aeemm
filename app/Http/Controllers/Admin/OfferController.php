<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;
use App\Models\Course;
use App\Models\Offer;

class OfferController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->cid){
            $offers=Offer::where('course_id',$request->cid)->get();
        }else{
            $offers=Offer::all();
        }
        $courses=Course::all();
        return Inertia::render('Admin/Offer',[
            'courses'=>$courses,
            'offers'=>$offers
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Validator::make($request->all(), [
            'code' => ['required'],
            'title_zh' => ['required']
        ])->validate();
        
        Offer::create($request->all());
        return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        Validator::make($request->all(), [
            'course_id' => ['required'],
            'code' => ['required'],
        ])->validate();
        
        $course=Offer::find($id);
        $course->course_id=$request->course_id;
        $course->code=$request->code;
        $course->title_zh=$request->title_zh;
        $course->title_en=$request->title_en;
        $course->apply_start=date('Y-m-d',strtotime($request->apply_start));
        $course->apply_end=date('Y-m-d',strtotime($request->apply_end));
        $course->course_start=date('Y-m-d',strtotime($request->course_start));
        $course->course_end=date('Y-m-d',strtotime($request->course_end));
        $course->price=$request->price;
        $course->early_price=$request->early_price;
        $course->member_price=$request->member_price;
        $course->description=$request->description;
        $course->remark=$request->remark;
        $course->publish=$request->publish;
        $course->save();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
