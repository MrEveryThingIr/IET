const buttonToShow=document.querySelector('.showByClickAddPart');
buttonToShow.addEventListener('click',showThis());
function showThis(){
    const main=document.getElementsByTagName('main');
    main.textContent="<?php include base_path('partials/admin/htmlComponents/createProjectForm.php')?>";
}