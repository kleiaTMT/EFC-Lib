<?php
    include 'logreq.php';
    $_SESSION['searchUser']='';

    $start = 0;
    $perpage = 9;

    if(isset($_GET['page'])){
        $page = $_GET['page'];
    } else
        $page = 1;

    $start_from = ($page-1) * $perpage ;

    $jQuery = "SELECT * FROM users ORDER BY uid LIMIT ?, ?";
    $jstm = $conn->prepare($jQuery);
    $jstm->bind_param("ii", $start_from, $perpage);
    $jstm->execute();
    $jres = $jstm->get_result();

    $kQuery = "SELECT * FROM users";
    $kstm = $conn->prepare($kQuery);
    $kstm->execute();
    $kres = $kstm->get_result();
    $ksum = mysqli_num_rows($kres);

    $oQuery = "SELECT DISTINCT company FROM users";
    $ostm = $conn->prepare($oQuery);
    $ostm->execute();
    $ores = $ostm->get_result();
    $dre = array();

    $pQuery = "SELECT DISTINCT usertype FROM users";
    $pstm = $conn->prepare($pQuery);
    $pstm->execute();
    $pres = $pstm->get_result();
    $ddre = array();

    while($ops = $ores->fetch_assoc()){
        array_push($dre, $ops['company']);
    }

    while($opp = $pres->fetch_assoc()){
        array_push($ddre, $opp['usertype']);
    }

    $hpages = ceil($ksum / $perpage);
?>
<input class="search" type="text" id="myInput" onkeyup="myFilter()" placeholder="Search for user's names..." />

<!-- ADD ACCOUNT BUTTON + MODAL -->
<button type="button" class="btn btn-primary search btn-sm" data-bs-toggle="modal" data-bs-target="#exampleModal">
    Add Account
</button>
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="false">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
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
                        <label for="passw">Re-Type Password: </label>
                    </div>
                    <div class="inpts">
                        <input name="emailAddr" type="email" placeholder="Company Email Address" required>
                        <input name="uname" type="text" placeholder="Full Name" required>
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
                        <input name="passw" type="password" placeholder="Password..." id="passw" required>        
                        <input name="passw" type="password" placeholder="Re-Type Password" required> 
                    </div>
                    <div id="message">
                        <p>Password must contain the following:</p>
                        <p id="letter" class="invalid">A <b>lowercase</b> letter</p>
                        <p id="capital" class="invalid">A <b>capital (uppercase)</b> letter</p>
                        <p id="number" class="invalid">A <b>number</b></p>
                        <p id="length" class="invalid">Minimum <b>8 characters</b></p>
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
    <thead class="sticky-top">
        <th width="5%">Action</th>
        <th width="4%">UID</th>
        <th width="18%">Email</th>
        <th width="26%">Name</th>
        <th width="12%">Company/Branch</th>
        <th width="10%">Account Type</th>
        <th width="10%">Date Created</th>
        <th width="10%">Last Visit</th>
    </thead>    
    <tbody>
        <?php 
            while ($row = $jres->fetch_assoc()) {  ?>
                <tr>
                    <td>
                        <!--EDIT USER BUTTON AND MODAL-->
                        <button type="button" class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editUser<?php echo $row['uid']?>">✎</button>
                        <div class="modal fade" id="editUser<?php echo $row['uid']?>" tabindex="-1" aria-labelledby="editUserLabel" aria-hidden="true">
                            <div class="modal-dialog modal-default modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h1 class="modal-title fs-5" id="editUserLabel">Edit User</h1>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <form method="POST">
                                        <div class="modal-body" id="editUserModal">
                                                <input type="hidden" name="uid" value="<?php echo $row['uid'] ?>">
                                            <label>Email:</label>
                                                <input type="text" name="emailAddr" value="<?php echo $row['emailAddr'] ?>">
                                            <label>Name:</label>
                                                <input type="text" name="uname" value="<?php echo $row['uname'] ?>">
                                            <label>Company/Branch:</label>
                                                <select name="company" id="company">
                                                    <?php
                                                        foreach($dre as $fire){
                                                            if($row['company'] == $fire){?>
                                                                <option value="<?php echo $fire; ?>" selected><?php echo $fire; ?></option> <?php
                                                            } else { ?>
                                                                <option value="<?php echo $fire; ?>"><?php echo $fire; ?></option><?php 
                                                            }
                                                        }
                                                    ?>
                                                </select><br>
                                            <label>Account Type:</label>
                                                <select name="usertype" id="usertype">
                                                    <?php
                                                        foreach($ddre as $utype){
                                                            if($row['usertype'] == $utype){?>
                                                                <option value="<?php echo $utype; ?>" selected> <?php
                                                                    switch($utype) {
                                                                        case '0':
                                                                            echo 'Regular';
                                                                            break;
                                                                        case '1':
                                                                            echo 'Independent';
                                                                            break;
                                                                        case '2':
                                                                            echo 'Admin';
                                                                            break;
                                                                    }?>
                                                                </option> <?php
                                                            } else { ?>
                                                                <option value="<?php echo $utype; ?>"><?php
                                                                    switch($utype) {
                                                                        case '0':
                                                                            echo 'Regular';
                                                                            break;
                                                                        case '1':
                                                                            echo 'Independent';
                                                                            break;
                                                                        case '2':
                                                                            echo 'Admin';
                                                                            break;
                                                                    }?>
                                                                </option><?php 
                                                            }
                                                        }
                                                    ?>
                                                </select><br>
                                            <label>New Password:</label>
                                                <input type="password" name="newpass">
                                            <label>Confirm Password:</label>
                                                <input type="password" name="cnewpass">
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                            <button type="submit" value="edittrue" name="edittrue" class="btn btn-primary">Save</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </td>
                    <td style="text-align:center"><b><?php echo $row["uid"]; ?></b></td>
                    <td><b><?php echo $row["emailAddr"]; ?></b></td>
                    <td><b><?php echo $row["uname"]; ?></b></td>
                    <td><b><?php echo $row["company"]; ?></b></td>
                    <td><b><?php 
                        switch($row["usertype"]){
                            case "1":
                                echo 'Independent';
                                break;
                            case "2":
                                echo 'Admin';
                                break;
                            default:
                                echo 'Regular';
                        } ?>
                    </b></td>
                    <td><b><?php echo $row["datecreate"]; ?></b></td>
                    <td><b>
                        <?php 
                            if($row["lastdate"] == "0000-00-00"){
                                echo 'No visits yet..';
                            } else {
                                echo $row["lastdate"];
                            }
                        ?>
                    </b></td>
                </tr> <?php
            }
        ?>
    </tbody>
</table>
<?php include "pagination.php" ?>
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
            td = tr[i].getElementsByTagName("td")[3];
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
    var myInput = document.getElementById("passw");
    var letter = document.getElementById("letter");
    var capital = document.getElementById("capital");
    var number = document.getElementById("number");
    var length = document.getElementById("length");

    // When the user clicks on the password field, show the message box
    myInput.onfocus = function() {
        document.getElementById("message").style.display = "block";
    }

    // When the user clicks outside of the password field, hide the message box
    myInput.onblur = function() {
        document.getElementById("message").style.display = "none";
    }

    // When the user starts to type something inside the password field
    myInput.onkeyup = function() {
        // Validate lowercase letters
        var lowerCaseLetters = /[a-z]/g;
        if(myInput.value.match(lowerCaseLetters)) {  
            letter.classList.remove("invalid");
            letter.classList.add("valid");
        } else {
            letter.classList.remove("valid");
            letter.classList.add("invalid");
        }
        
        // Validate capital letters
        var upperCaseLetters = /[A-Z]/g;
        if(myInput.value.match(upperCaseLetters)) {  
            capital.classList.remove("invalid");
            capital.classList.add("valid");
        } else {
            capital.classList.remove("valid");
            capital.classList.add("invalid");
        }

        // Validate numbers
        var numbers = /[0-9]/g;
        if(myInput.value.match(numbers)) {  
            number.classList.remove("invalid");
            number.classList.add("valid");
        } else {
            number.classList.remove("valid");
            number.classList.add("invalid");
        }
        
        // Validate length
        if(myInput.value.length >= 8) {
            length.classList.remove("invalid");
            length.classList.add("valid");
        } else {
            length.classList.remove("valid");
            length.classList.add("invalid");
        }
    }

    document.getElementById('chngpw').onchange = function() {
        document.getElementById('pwtest').disabled = !this.checked;
    };
</script>
<style>
    .modal-dialog-scrollable {
        overflow: y;
    }
    .labls { 
        float: left;
        width: 30%;
        text-align: left;
    }
    .labls label {
        padding: 2.3px;
    }
    .inpts {
        float: right;
        width: 70%;
    }
    .inpts input {
        width: 80%;
    }
    .modal-footer {
        width: 100%;
    }
    #message {
        display:none;
        background: #f1f1f1;
        color: #000;
        position: relative;
        padding: 20px;
        margin-top: 10px;
    }

    #message p {
        padding: 10px 35px;
        font-size: 12px;
    }

    /* Add a green text color and a checkmark when the requirements are right */
    .valid {
        color: green;
    }

    .valid:before {
        position: relative;
        left: -35px;
        content: "✔";
    }

    /* Add a red text color and an "x" when the requirements are wrong */
    .invalid {
        color: red;
    }

    .invalid:before {
        position: relative;
        left: -35px;
        content: "✖";
    }
</style>