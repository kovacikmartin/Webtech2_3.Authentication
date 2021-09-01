function nameWarn()
{
    let errorMsg = document.getElementById("formNameWarn");
    let name = document.getElementById("formName");

    if(name.value.length == 0)
    {
        errorMsg.textContent = "Name cannot be empty !";
        name.style.border = "1px solid red";
        return false;
    }
    else
    {
        name.style.border = "1px solid lightgrey";
        errorMsg.textContent = "";
        return true;
    }
}

function surnameWarn()
{
    let errorMsg = document.getElementById("formSurnameWarn");
    let surname = document.getElementById("formSurname");

    if(surname.value.length == 0)
    {
        errorMsg.textContent = "Surname cannot be empty !";
        surname.style.border = "1px solid red";
        return false;
    }
    else
    { 
        surname.style.border = "1px solid lightgrey";    
        errorMsg.textContent = "";
        return true;
    }
}

function emailWarn()
{
    let errorMsg = document.getElementById("formEmailWarn");
    let email = document.getElementById("formEmail");
    let emailFormat = /^([A-Za-z0-9_\-\.]){3,}\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/;

    if(email.value.length == 0)
    {
        errorMsg.textContent = "Email cannot be empty !";
        email.style.border = "1px solid red";
        return false;
    }
    
    if(!emailFormat.test(email.value))
    {
        errorMsg.textContent = "Email in wrong format !";
        email.style.border = "1px solid red";
        return false;
    }
    else
    {
        email.style.border = "1px solid lightgrey";    
        errorMsg.textContent = "";
        return true;
    }
}

function nicknameWarn()
{
    let errorMsg = document.getElementById("formNicknameWarn");
    let nickname = document.getElementById("formNickname");

    if(nickname.value.length == 0)
    {
        errorMsg.textContent = "Nickname cannot be empty !";
        nickname.style.border = "1px solid red";
        return false;
    }
    else
    {
        nickname.style.border = "1px solid lightgrey";
        errorMsg.textContent = "";
        return true;
    }
}

function passWarn()
{
    let errorMsg = document.getElementById("formPassWarn");
    let pass = document.getElementById("formPass");

    if(pass.value.length == 0)
    {
        errorMsg.textContent = "Password cannot be empty !";
        pass.style.border = "1px solid red";
        return false;
    }
    else
    {
        pass.style.border = "1px solid lightgrey";
        errorMsg.textContent = "";
        return true;
    }
}

function passRptWarn()
{
    let errorMsg = document.getElementById("formPassRptWarn");
    let passRpt = document.getElementById("formPassRpt");
    let pass = document.getElementById("formPass");

    if(passRpt.value.length == 0)
    {
        errorMsg.textContent = "Password cannot be empty !";
        passRpt.style.border = "1px solid red";
        return false;
    }
    else if(passRpt.value !== pass.value)
    {
        errorMsg.textContent = "Passwords do not match !";
        passRpt.style.border = "1px solid red";
        return false;
    }
    else
    {
        passRpt.style.border = "1px solid lightgrey";
        errorMsg.textContent = "";
        return true;
    }
}

function formVal()
{
    return [nameWarn(), surnameWarn(), emailWarn(), nicknameWarn(), passWarn(), passRptWarn()].every(Boolean);
}

$(document).ready(function(){

    $('#userLogsTable').DataTable({
        "order": [],
        "searching": false,
        "paging": false,
        "info": false,
        "scrollY": "70%",
        "scrollCollapse": true
    }).columns.adjust();
});