<?php
    include 'logreq.php';
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
                    <td><b>'.$row["uname"].'</b></td>
                    <td><b>'.$row["emailAddr"].'</b></td>
                    <td><b>'.$row["company"].'</b></td>
                    <td>'.$row["datecreate"].'</td>';
                    if($row["lastdate"] == "0000-00-00"){
                        echo '<td>No visits yet..</td>';
                    } else {
                        echo '<td>'.$row["lastdate"].'</td>';
                    }
                    echo '
                    <td>
                        <a href="">Edit</a>
                        <a href="">Disable</a>
                    </td>
                </tr>
            ';
        }
    }
?>
<input type="text" id="myInput" onkeyup="myFilter()" placeholder="Search for user's names..." />

<!-- Button trigger modal -->
<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
    Add Account
</button>

<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Register User</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="./modular/logreq.php" method="POST" id="myForm">
                <div class="modal-body">
                    <div class="labls">
                        <label for="emailAddr">Email Address: </label>
                        <label for="uname">Full Name: </label>
                        <label for="company">Company: </label>
                        <label for="passw">Password: </label>
                    </div>
                    <div class="inpts">
                        <input name="emailAddr" type="email" placeholder="Company Email Address">
                        <input name="uname" type="text" placeholder="Full Name">
                        <select name="company" id="company">
                            <option value="EFC-HO">EFC-HO</option>
                            <option value="EFC-NLDC">EFC-NLDC</option>
                            <option value="EFC-SLDC">EFC-SLDC</option>
                            <option value="EFC-DHY">EFC-DHY</option>
                            <option value="EFC-Vis">EFC-Vis</option>
                            <option value="EFC-Min">EFC-Min</option>
                            <option value="Kalbe">Kalbe</option>
                            <option value="Butter-and-Salt">Butter-and-Salt</option>
                            <option value="Agency">Agency</option>
                        </select>
                        <input name="passw" type="password" placeholder="Password...">         
                    </div>               
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        Cancel
                    </button>
                    <button type="submit" name="signup" class="btn btn-primary">   
                        Add User
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<table id="myTable">
    <thead>
        <th>ID</th>
        <th>Name</th>
        <th>Email</th>
        <th>Company/Branch</th>
        <th>Date Created</th>
        <th>Last Visit</th>
        <th>Action</th>
    </thead>    
    <tbody>
        <?php print printUsers($_SESSION['searchUser']); ?>
    </tbody>
</table>
<script type="text/javascript">
    function myFilter() {
        // Declare variables
        var input, filter, table, tr, td, i, txtValue;
        input = document.getElementById("myInput");
        filter = input.value.toUpperCase();
        table = document.getElementById("myTable");
        tr = table.getElementsByTagName("tr");

        // Loop through all table rows, and hide those who don't match the search query
        for (i = 0; i < tr.length; i++) {
            td = tr[i].getElementsByTagName("td")[1];
            if (td) {
                txtValue = td.textContent || td.innerText;
                if (txtValue.toUpperCase().indexOf(filter) > -1) {
                    tr[i].style.display = "";
                } else {
                    tr[i].style.display = "none";
                }
            }
        }
    }
</script>
<style>
    .modal-backdrop {
        display: none;
    }
    .labls { 
        float: left;
        width: 25%;
        text-align: left;
    }
    .labls label {
        padding: 2.3px;
    }
    .inpts {
        float: right;
        width: 75%;
    }
    .inpts input {
        width: 80%;
    }
    .modal-footer {
        width: 100%;
    }
</style>