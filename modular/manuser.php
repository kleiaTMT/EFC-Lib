<?php
    include 'logreq.php';
    @session_start();

    
    $_SESSION['searchUser']='';

    function printUsers($searchUser){

        global $conn;

        $seqel = 'SELECT * FROM users WHERE uname LIKE ? ';
        $stetmt = $conn->prepare($seqel);
        $searchUserPerc = '%'.$searchUser.'%';
        $stetmt->bind_param('s', $searchUserPerc);
        $stetmt->execute();
        $results = $stetmt->get_result();
        
        if (mysqli_num_rows($results) == 0){
            echo 'No users are registered yet...';
        }
        while ($row = $results->fetch_assoc()) {  
            echo '
                <tr>
                    <td><b>'.$row["uid"].'</b></td>
                    <td>'.$row["uname"].'</td>
                    <td>'.$row["emailAddr"].'</td>
                    <td>'.$row["datecreate"].'</td>
                    <td>'.$row["lastdate"].'</td>
                    <td>
                        <a href="">Edit</a>
                        <a href="">Disable</a>
                    </td>
                </tr>
            ';
        }
    }
    echo '
    
    <form action="" id="searchuser">
        <input type="text" placeholder="Search...">
    </form>
    <button id="submit">Submit</button>
    <table>
        <thead>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Date Created</th>
            <th>Last Visit</th>
            <th>Action</th>
        </thead>    
        <tbody>';    
            print printUsers($_SESSION['searchUser']); 
        '</tbody>
    </table>
    ';
?>
<script type="text/javascript">
    var postData = "text";
    $('#submit').on('click',function(){
        $.ajax({
            type: "post",
            url: ".modular/manuser.php",
            data:  $("#searchuser").serialize(),
            contentType: "application/x-www-form-urlencoded",
                success: function(response) { // on success..
            $('#replaceThis').html(response); // update the DIV
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.log(errorThrown);
            }
        })
    });
</script>

