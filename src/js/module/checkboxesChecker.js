import $ from 'jquery'


export default function (el){
    el.find('.checkbox:checked').each( function () {
        $(this).attr('check','true');
        el.trigger('click');
    } );
    el.find('.checkbox:not(:checked)').each( function () {
        $(this).attr('check','false');
    } )
    el.find('.radio:checked').each( function () {
        $(this).attr('check','true');
        el.trigger('click');
    } );
    el.find('.radio:not(:checked)').each( function () {
        $(this).attr('check','false');
    } )
}