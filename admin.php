<?php

if($_SESSION["logged_in"]){
    echo "hello Mr".$_SESSION['username']; 
}