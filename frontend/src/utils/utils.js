import { TZDate } from '@toast-ui/calendar'

export function clone(date) {
  return new TZDate(date)
}

export function addHours(d, step) {
  const date = clone(d)
  date.setHours(d.getHours() + step)

  return date
}

export function addDate(d, step) {
  const date = clone(d)
  date.setDate(d.getDate() + step)

  return date
}

export function subtractDate(d, steps) {
  const date = clone(d)
  date.setDate(d.getDate() - steps)

  return date
}
//Format before save
export function formatFn(date) {
  date.toLocaleDateString('pt-PT', { year: 'numeric', month: '2-digit', day: '2-digit' })
  return `${date.getFullYear()}-${date.getMonth() + 1}-${date.getDate()}`
}

//convert to display
export function formatFnDisplay(date) {
  const split = date.split(' ')
  const data = new Date(split[0]).toLocaleDateString('pt-PT', { year: 'numeric', month: '2-digit', day: '2-digit' })
  const time = split[1]
  return data + ' ' + time
}
