export default function (el, ex = null) {
    if ( ex === null ){
        let obj = {
            'question': el.val().trim(),
            'checkbox': el.attr('check'),
            'radio': el.attr('check'),
        }
        return obj[el.attr('question-type')];
    }
    
    let change = {
        'question': el.val(ex),
        'checkbox': el.attr('check',ex),
        'radio': el.attr('check',ex),
    }

    change[el.attr('question-type')];
}