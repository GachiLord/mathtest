export default function(what){

    switch(what){
        case 'text': return '<input type="text" class="question form-control m-1 d-inline" question-type="question" style="width: 150px;">'; 
        case 'checkbox': return '<input type="checkbox" class="checkbox question form-check-input m-1" question-type="checkbox">';
        case 'radio': return '<input type="radio" class="radio question form-check-input m-1" question-type="radio">';
        case 'img': return `<img src="${prompt('Введите ссылку на изображение')}" style="max-height: 400px; max-width: -webkit-fill-available; max-width: -moz-available;">`;
    }
}