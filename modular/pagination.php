<nav aria-label="...">
        <ul class="pagination justify-content-center">
            <?php 
                if($page>1) { ?>
                    <li class="page-item">
                        <a class="page-link" href="?page=<?php echo $page-1;?>">
                            Previous
                        </a>
                    </li> <?php 
                } else { ?>
                    <li class="page-item disabled">
                        <a class="page-link">
                            Previous
                        </a>
                    </li> <?php
                }
                for($i=1;$i<=$hpages;$i++) {
                    if($page == $i) {
                        echo '<li class="page-item active">';
                    } else {
                        echo '<li class="page-item">';
                    }
                    echo '
                        <a class="page-link" href="?page='.$i.'">
                            '.$i.'
                        </a>
                    </li>';
                }
                if($page<$hpages) { ?>
                    <li class="page-item">
                        <a class="page-link" href="?page=<?php echo $page+1;?>">
                            Next
                        </a>
                    </li> <?php 
                } else { ?>
                    <li class="page-item disabled">
                        <a class="page-link" href="#">
                            Next
                        </a>
                    </li> <?php
                } 
            ?>
        </ul>
    </nav>