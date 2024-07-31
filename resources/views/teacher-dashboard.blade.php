
<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
</head>
<body>
    <h2 class="page_header" style="text-align: center;">Students</h2>
    <div class="container">
        <button type="button" id="add-product-btn" class="btn btn-success" data-toggle="modal" data-target="#myModal">Add Student</button>
        <a href="{{url('/log-out')}}">
            <button type="button" class="btn btn-danger">Logout</button>
        </a>
        <a href="{{url('/teacher-dashboard?sort=1')}}">
            <button type="button" class="btn btn-info">Sort</button>
        </a>
        @if (count($studentsData) > 0)
            <table class="table table-striped">
                <thead>
                <tr>
                    <th>Name</th>
                    <th>Subject</th>
                    <th>Mark</th>
                    <th></th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                    @foreach ($studentsData as $key => $student)
                        <tr>
                            <td><input type="text" class="" id="edit_name{{$student->id}}" value="{{$student->name}}" disabled></td>
                            <td><input type="text" class="" id="edit_subject{{$student->id}}" value="{{$student->subject}}" disabled></td>
                            <td><input type="text" class="" id="edit_mark{{$student->id}}" value="{{$student->mark}}" disabled></td>
                            <td><p style="color: red" id="edit_student_message{{$student->id}}"></p></td>
                            @if ($student->teacher_id == $teacherId)
                                <td>
                                    <button type="button" class="btn btn-warning" onclick='editStudent({{$student->id}})'>Edit</button>
                                    <a href='#' onclick='confirmDelete({{"$student->id"}})'>
                                        <button type="button" class="btn btn-danger">Delete</button>
                                    </a>
                                </td>
                            @else
                                <td></td>
                            @endif
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p>Students are not available</p>
        @endif
    </div>

    <div id="myModal" class="modal fade" role="dialog">
        <div class="modal-dialog">
    
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Add Student</h4>
            </div>
            <div class="modal-body" id="">
                <div class="form-container">
                    <form >
                        <div class="input-group">
                            <label for="name">Name</label>
                            <input class="form-control" type="text" id="name" name="name" required>
                        </div>
                        <div class="input-group">
                            <label for="subject">Subject</label>
                            <input class="form-control" type="text" id="subject" name="subject" required>
                        </div>
                        <div class="input-group">
                            <label for="mark">Mark</label>
                            <input class="form-control" type="number" id="mark" name="mark" required>
                        </div>
                        <br>
                        <p style="color: red" id="add-student-message"></p>
                        <br>
                        <button type="button" id="add-student-btn">Submit</button>
                    </form>
                </div>
            </div>
            <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    
        </div>
    </div>

    <script>

        $("#add-student-btn").click(function(){
            var name = $('#name').val();
            var subject = $('#subject').val();
            var mark = $('#mark').val();
            $.ajax({
                type: 'POST',
                url: "{{url('add-student')}}",
                data: {name: name, subject: subject, mark: mark, _token: '{{csrf_token()}}'},
                success: function (data) {
                    $('#add-student-message').html(data.message);
                    setTimeout(function() {
                        $('#add-student-message').html('');
                        location.reload();
                    }, 2000);
                },
            });
        });

        function editStudent(StudentId) {
            var nameField = $('#edit_name'+StudentId);
            var subjectField = $('#edit_subject'+StudentId);
            var markFied = $('#edit_mark'+StudentId);
            if (nameField.prop('disabled')) {
                nameField.prop('disabled', false);
                subjectField.prop('disabled', false);
                markFied.prop('disabled', false);
            } else {
                var name = nameField.val();
                var subject = subjectField.val();
                var mark = markFied.val();
                $.ajax({
                    type: 'POST',
                    url: "{{url('edit-student')}}",
                    data: {StudentId: StudentId, name: name, subject: subject, mark: mark, _token: '{{csrf_token()}}'},
                    success: function (data) {
                        $('#edit_student_message'+StudentId).html(data.message);
                        setTimeout(function() {
                            $('#edit_student_message'+StudentId).html('');
                            location.reload();
                        }, 2000);
                    },
                });
            }
            
        }

        function confirmDelete(StudentId) {
            if (confirm("Are you sure you want to delete this student?")) {
                $.ajax({
                    type: 'POST',
                    url: "{{url('delete-student')}}",
                    data: {StudentId: StudentId, _token: '{{csrf_token()}}'},
                    success: function (data) {
                        $('#edit_student_message'+StudentId).html(data.message);
                        setTimeout(function() {
                            $('#edit_student_message'+StudentId).html('');
                            location.reload();
                        }, 2000);
                    },
                });
            }
        }

    
    </script>
</body>
</html>