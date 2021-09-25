export default function(what){

    switch(what){
        case 'text': return '<input type="text" class="question" question-type="question">'; 
        case 'checkbox': return '<input type="checkbox" class="checkbox question" question-type="checkbox">';
        case 'radio': return '<input type="radio" class="radio question" question-type="radio">';
        case 'img': return `<img src="${prompt('Введите ссылку на изображение')}" style="max-height: 400px; max-width: -webkit-fill-available; max-width: -moz-available;">`;
    }
}