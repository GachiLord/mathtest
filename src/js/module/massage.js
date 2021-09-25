import $ from 'jquery'


export default function (massage, type = 'massage'){
    let output = `<div class="${type}">${massage}</div>`;
    if ( /div/.test(massage) ) output = massage; 
    $('body').prepend(output);
    setTimeout(() => {
        $('.error').animate({opacity:0})
        $('.massage').animate({opacity:0})
    }, 2000); 
}