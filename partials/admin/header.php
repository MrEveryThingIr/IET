<!DOCTYPE html>
<?php 
include __DIR__."/../../init.php";

?>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>سیستم مدیریت و پیگیری پروژه های تامکار</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"
        rel="stylesheet"

    >


    <style>

.table-scroll {
    overflow-x: auto;
    white-space:nowrap;
}
            /* Make sure the html and body take up the full height */
    html, body {
        height: 100%;
        margin: 0;
    }

    body {
        display: flex;
        flex-direction: column;
    }

    /* Main content will expand to fill the available space */
    main {
        flex: 1;
    }

    </style>
</head>
<body>
