<?php

namespace App\Http\Controllers;
use App\Models\Teachers;
use App\Models\Students;
use App\Models\StudentDetails;
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
            session()->put('teacher_id', $admin->id);
            session()->save();
            $status = 1;
            $messge = 'success';
        } else {
            $status = 2;
            $messge = 'Please check email and password';

        }
        return response()->json(['status' => $status, 'messge' => $messge]);
    }

    public function dashboard(Request $request) {
        $studentsData = Students::select('students.*','student_details.subject','student_details.mark')->join('student_details','student_details.student_id','=','students.id')->get();
        if (!empty($request->sort) && $request->sort == 1) {
            $studentsData = Students::select('students.*','student_details.subject','student_details.mark')->join('student_details','student_details.student_id','=','students.id')->orderBy('student_details.mark', 'asc')->get();
        }
        $teacherId = session()->get('teacher_id');
        return view('teacher-dashboard', compact('studentsData', 'teacherId'));
    }

    public function addStudent(Request $request) {
        $name = $request->name;
        $subject = $request->subject;
        $mark = $request->mark;
        $teacherId = session()->get('teacher_id');
        if (!isset($request->StudentId)) {
            $checkStudent = Students::where('name', $name)->first();
            if (empty($checkStudent)) {
                $student = new Students;
                $student->name = $name;
                $student->teacher_id = $teacherId;
                $student->save();
                $this->insertToStudentDetails(1, $student->id,$subject, $mark);
                $status = 1;
                $message = 'New Student added successfully';
            } else {
                $checkStudentdetils = StudentDetails::where('student_id', $checkStudent->id)->where('subject', $subject)->first();
                if (!empty($checkStudentdetils)) {
                    $checkStudentdetils->subject = $subject;
                    $checkStudentdetils->mark = $mark;
                    $checkStudentdetils->save();
                    $status = 2;
                    $message = 'Student details updated';
                } else {
                    $this->insertToStudentDetails(1, $checkStudent->id,$subject, $mark);
                    $status = 2;
                    $message = 'Student details updated';
                }
            }
        } else {
            // edit student code
            $studentDetails = Students::find($request->StudentId);
            if ($studentDetails->teacher_id == $teacherId) {
                $student = Students::find($request->StudentId);
                $student->name = $name;
                $student->teacher_id = $teacherId;
                $student->save();
                $this->insertToStudentDetails(2, $request->StudentId->id,$subject, $mark);
                $status = 2;
                $message = 'Student details updated';
            } else {
                $status = 4;
                $message = 'Please check your edit privileges';
            }
        }
        return Response::json(['status' => $status, 'message'=> $message], 200);

    }

    public function deleteStudent(Request $request) {
       $StudentId = $request->StudentId;
       $studentDetails = Students::find($StudentId);
       $teacherId = session()->get('teacher_id');
       if ($studentDetails->teacher_id == $teacherId) {
           Students::where('id', $StudentId)->delete();
           $status = 3;
           $message = 'Deleting student details..';
       } else {
            $status = 4;
            $message = 'Please check your delete privileges';
       }
       return Response::json(['status' => $status, 'message'=> $message], 200);
    } 

    public function logout() {
        session()->flush();
        return redirect('/');
    }

    public function insertToStudentDetails($type, $StudentId,$subject, $mark) {
        // $type == 1 is insertind and $type == 2 is updating
        if ($type == 1) {
            $studentDetails = new StudentDetails;
            $studentDetails->student_id = $StudentId;
        } else {
            $studentDetails = StudentDetails::where('student_id', $StudentId)->where('subject', $subject)->first();
        }
        $studentDetails->subject = $subject;
        $studentDetails->mark = $mark;
        $studentDetails->save();
    }
}
