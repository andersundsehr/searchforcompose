<?php
require 'functions.php';

if(isset($_POST['submit']))
{
    startAllContainersOfProject($_POST['projectName']);
}