function FocusOn(obj)
{
    //obj.parentNode.parentNode.className="FieldOutlineFocus";
    var out = document.getElementById("FieldOutline");
    out.className="FieldOutlineFocus";
    obj.className="FieldInputFocus";        
    if (obj.value=='Cadastre seu e-mail para receber as novidades.'){
        obj.value="";
    }
}
function FocusOff(obj)
{
    if (obj.value==''){
        obj.value="Cadastre seu e-mail para receber as novidades.";
        //obj.parentNode.parentNode.className="FieldOutline";
        var out = document.getElementById("FieldOutline");
        out.className="FieldOutline";
        obj.className="FieldInput";
        //obj.style.color= '#cdcdcd';

    }else{
        //obj.parentNode.parentNode.className="FieldOutline";
        var out = document.getElementById("FieldOutline");
        out.className="FieldOutline";
        obj.className="FieldInput";
        //obj.style.color= '#595959';
    }

}