export default function(seconds){
    let minutes = (seconds/60) >= 1 ? seconds/60 : 0;
    let seconds = minutes === 0 ? seconds : (minutes * 60) - seconds;

    return `Минут: ${minutes}, Секунд:${seconds}`;
}