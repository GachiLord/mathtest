import $ from 'jquery'
import isMob from './IsMobile';
import isJson from './isJson';


export default function (massage = 'Готово', type = true){
    let output = "";
    let deviceType = isMob ? 'mobile-alert' : 'alert';
    let jsTypeOfMsg = isJson(massage);
    massage = jsTypeOfMsg ? JSON.parse(massage) : massage;
    

    if ( jsTypeOfMsg ) {
        if (massage.type == true) output = `<div class='${deviceType} alert-success alert' role='alert' style='position: fixed;'>${massage.massage}</div>`;
        else output = `<div class='${deviceType} alert-danger alert' role='alert' style='position: fixed;'>${massage.massage}</div>`;
    }
    else {
        if (type) output = `<div class='${deviceType} alert-success alert' role='alert' style='position: fixed;'>${massage}</div>`;
        else output = `<div class='${deviceType} alert-danger alert' role='alert' style='position: fixed;'>${massage}</div>`;
    } 
    

    $('body').prepend(output);
    setTimeout(() => {
        $('.alert').animate({opacity:0})
    }, 3000); 
}