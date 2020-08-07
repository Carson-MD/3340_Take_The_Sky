function validate_matching()
{
    var str = document.getElementById("text_hint").innerHTML;
    var result = str.substring(1,2);

    if (result == "o")
    {
        return true;
    }
        
    else
    {
        event.preventDefault();
        return false;
    }
}

function reg_validate_matching()
{
    var str = document.getElementById("text_hint").innerHTML;


    
    if (str !== "")
    {
        result = str.substring(1,2);
    
        if (result == "o")
        {
            var pw = document.getElementById("password").value;
            var pw2 = document.getElementById("password2").value;
            if (pw == pw2)
            {
                return true;

            }
            else
            {
                document.getElementById("pw_hint").innerHTML = "Passwords do not match";
                event.preventDefault();
                return false;
            }
        }
        else
        {
            event.preventDefault();
            return false;
        } 
    }
}
   
function reg_validate_matching_2()
{
    str = document.getElementById("text_hint_2").innerHTML;
    
    if (str !== "")
    {
        result = str.substring(1,2);
    
        if (result == "o")
        {
           var pw = document.getElementById("pilot_pw").value;
            var pw2 = document.getElementById("pilot_pwc").value;
            if (pw == pw2)
            {
                return true;
            }
            else
            {
                document.getElementById("pw_hint_2").innerHTML = "Passwords do not match";
                event.preventDefault();
                return false;
            }
        }
        else
        {
            event.preventDefault();
            return false;
        } 
    }
}

function show_hint()
{
    var user_name = document.getElementById("user_name").value;
    var password = document.getElementById("password").value;


    if (password !== "")
    {
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4) 
            {
                var msg = this.responseText;
                document.getElementById("text_hint").innerHTML = msg;
            }
        };
        xmlhttp.open("GET", "get_info.php?user_name="+user_name+"&password="+password, true);
        xmlhttp.send();
    }
}

function reg_show_hint()
{
    var user_name = document.getElementById("user_name").value;
    var password = document.getElementById("password").value;
    var password2 = document.getElementById("password2").value;


    if (user_name !== "")
    {
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4) 
            {
                var msg = this.responseText;
                document.getElementById("text_hint").innerHTML = msg;
            }
        };
        xmlhttp.open("GET", "register_info.php?user_name="+user_name, true);
        xmlhttp.send();
    }
    if (password != password2)
    {
        document.getElementById("text_hint").innerHTML = "Passwords do not match";
    }
    
}

function reg_show_hint_2()
{
    var user_name = document.getElementById("pilot_un").value;


    if (user_name !== "")
    {
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4) 
            {
                var msg = this.responseText;
                document.getElementById("text_hint_2").innerHTML = msg;
            }
        };
        xmlhttp.open("GET", "register_info.php?user_name="+user_name, true);
        xmlhttp.send();
    }
}

function set_seats()
{
    var seats = document.getElementById("passengers").getElementsByTagName("option");
    var model = document.getElementById("model").value;
    
    if (model == 1)
    {
        seats[1].disabled = true;
        seats[2].disabled = true;
        seats[3].selected = true;
    }
    else
    {
        seats[1].disabled = false;
        seats[2].disabled = false;
    }
}

function toggle_spinner()
{
    var spinner = document.getElementById("spinner");
    spinner.style.display = "block";
    document.getElementById("spin_msg").innerHTML = "<b>Fetching Reults, Please Wait</b>";
}