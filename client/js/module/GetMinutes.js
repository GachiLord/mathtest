export default function(input){
    let minutes = Math.floor( (input/60) >= 1 ? input/60 : 0 );
    let seconds = minutes === 0 ? input : input - (minutes * 60);


    return `${minutes} минут ${seconds} секунд`;
}