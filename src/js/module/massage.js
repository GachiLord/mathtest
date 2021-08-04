import $ from 'jquery'


export default function (massage, type = 'massage'){
    $('body').prepend(`<div class="${type}">${massage}</div>`);
    setTimeout(() => {
        $('.error').animate({opacity:0})
        $('.massage').animate({opacity:0})
    }, 2000); 
}