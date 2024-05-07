<?php
    include 'logreq.php';
    @session_start();

    
    $_SESSION['searchFile']='';

    function printFiles($searchFile){

        global $conn;

        $seqel = 'SELECT * FROM files WHERE name LIKE ? ';
        $stetmt = $conn->prepare($seqel);
        $searchFilePerc = '%'.$searchFile.'%';
        $stetmt->bind_param('s', $searchFilePerc);
        $stetmt->execute();
        $results = $stetmt->get_result();
        
        if (mysqli_num_rows($results) == 0){
            echo 'No files are stored yet...';
        }
        while ($row = $results->fetch_assoc()) {  
            echo '
                <tr>
                    <td><b>'.$row["filID"].'</b></td>
                    <td>'.$row["name"].'.'.$row["ftype"].'</td>
                    <td>'.$row["dirGroup"].'</td>
                    <td>'.$row["dateup"].'</td>
                    <td>'.$row["size"].'</td>
                    <td>'.$row["downloads"].'</td>
                    <td>
                        <a href="">Edit</a>
                        <a href="">Hide</a>
                    </td>
                </tr>
            ';
        }
    }
    echo '
    <form action="" id="searchFile">
        <input type="text" placeholder="Search...">
    </form>
    <button id="submit">Submit</button>
    <table>
        <thead>
            <th>File ID</th>
            <th>Name</th>
            <th>Folder Location</th>
            <th>Date Uploaded</th>
            <th>Size</th>
            <th>Downloads</th>
            <th>Action</th>
        </thead>    
        <tbody>';    
            print printFiles($_SESSION['searchFile']); 
        '</tbody>
    </table>
    ';
?>
<script type="text/javascript">
    var postData = "text";
    $('#submit').on('click',function(){
        $.ajax({
            type: "post",
            url: ".modular/manfile.php",
            data:  $("#searchFile").serialize(),
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

