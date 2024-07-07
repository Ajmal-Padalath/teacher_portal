<?php

namespace App\Http\Controllers;
use App\Models\Teachers;
use App\Models\Students;
use Response;
use Session;

use Illuminate\Http\Request;

class TeacherController extends Controller
{
    public function checkLogin(Request $request) {
        $email = $request->email;
        $password = $request->password;
        $admin = Teachers::where('email', $email)->where('password', $password)->first();
        if($admin) {
            session()->put('name', $admin->name);
            session()->save();
            $status = 1;
            $messge = 'success';
        } else {
            $status = 2;
            $messge = 'Please check email and password';

        }
        return response()->json(['status' => $status, 'messge' => $messge]);
    }

    public function dashboard() {
        $studentsData = Students::get();
        return view('teacher-dashboard', compact('studentsData'));
    }

    public function addStudent(Request $request) {
        $name = $request->name;
        $subject = $request->subject;
        $mark = $request->mark;
        if (!isset($request->StudentId)) {
            $checkStudent = Students::where('name', $name)->where('subject', $subject)->first();
            if (empty($checkStudent)) {
                $student = new Students;
                $student->name = $name;
                $student->subject = $subject;
                $student->mark = $mark;
                $student->save();
                $status = 1;
                $message = 'New Student added successfully';
            } else {
                $studentId = $checkStudent->id;
                $student = Students::find($studentId);
                $student->name = $name;
                $student->subject = $subject;
                $student->mark = $mark;
                $student->save();
                $status = 2;
                $message = 'Student details updated';
            }
        } else {
            // edit student code
            $student = Students::find($request->StudentId);
            $student->name = $name;
            $student->subject = $subject;
            $student->mark = $mark;
            $student->save();
            $status = 2;
            $message = 'Student details updated';
        }
        return Response::json(['status' => $status, 'message'=> $message], 200);

    }

    public function deleteStudent(Request $request) {
       $StudentId = $request->StudentId;
       Students::where('id', $StudentId)->delete();
       return Response::json(['status' => 3, 'message'=> 'Deleting student details..'], 200);
    } 

    public function logout() {
        session()->flush();
        return redirect('/');
    }
}
