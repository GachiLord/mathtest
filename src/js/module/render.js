import $ from 'jquery'

export default function(selector, content, classAttr){
    for (const key in content) {
        if (Object.hasOwnProperty.call(content, key)) {
            selector.append(`<div id=${key} class="${classAttr}"></div>`);
            const element = content[key];


            Object.keys(element).forEach( item => {
                $(`#${key}`).append(`<div class=${item}> ${element[item]}</div>`);
            } );
        }
    }
}