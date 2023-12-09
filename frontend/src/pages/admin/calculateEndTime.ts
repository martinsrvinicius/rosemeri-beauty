//Function to calculate the duration of the service to set the end time on calendar
/*export function calculateEndTime(service: string, start: string, time: string) {
  //Start is the begining of the event date and hours
  let split = start.split(' ')
  //Split hours and minutes
  let splitStart = split[1].split(':')

  let hour = null
  let min = null
  let duration = null
  if (Number(time) == 560) {
    console.log('60#_ : ', split[0] + ' ' + (Number(splitStart[0]) + 1) + ':' + splitStart[1])
    return new Date(split[0] + ' ' + (Number(splitStart[0]) + 1) + ':' + splitStart[1])
  } else if (Number(time) > 560) {
    //transform from minutes to hours and the split by the dot
    let toMinutes = Number(time) / 60
    duration = String(toMinutes).split('.')
    hour = Number(splitStart[0]) + Number(duration[0])
    min = Number(time) % 60

    //Keep the minutes with two digits
    if (String(min).length < 2) {
      let m = min + '0'
      min = m
    }

    //If time past 23h then only set the minutes to 59
    if (hour > 24) {
      hour = 23
      min = 59
    }
    //console.log("DATA: ", split[0] + ' ' + hour + ':' + min)
    return new Date(split[0] + ' ' + hour + ':' + min)
  } else {
    //Convert to minutes
    let toMinutes = Number(splitStart[0]) * 60
    toMinutes += Number(time) + Number(splitStart[1])
    console.log('#MINUTES: ', toMinutes)

    //Convert to hour
    let toHour = toMinutes / 60
    console.log('#HOURS: ', toHour)
    let split = String(toHour).split('.')
    if (split[1]) {
      let min = (Number(split[1]) * 60) / 100
      if (min) console.log('#min: ', min.toFixed(0))
    }

    return new Date(split[0] + ' ' + toHour + ':' + min)
  }
}*/
