export const emptyFilters = Object.freeze({ search: '', specialty: '', date: '' })

export function isAvailableOnDate(doctor, date) {
  return doctor.workDays.includes(date.getDay())
}

export function normalize(text) {
  return text.toLowerCase().normalize('NFD').replace(/[\u0300-\u036f]/g, '')
}

export function todayInCenter() {
  const day = new Intl.DateTimeFormat('sv-SE', { timeZone: 'America/Santo_Domingo' }).format(new Date())
  return new Date(`${day}T12:00:00`)
}

export function nextDay(doctor, today) {
  for (let offset = 0; offset < 7; offset += 1) {
    const day = new Date(today)
    day.setDate(day.getDate() + offset)
    if (isAvailableOnDate(doctor, day)) return offset
  }
  return 7
}

export function matchesDoctor(doctor, filters) {
  const text = normalize(`${doctor.name} ${doctor.specialties.join(' ')} ${doctor.subspecialty || ''}`)
  if (!text.includes(normalize(filters.search.trim()))) return false
  if (filters.specialty && !doctor.specialties.includes(filters.specialty)) return false
  if (filters.date && !isAvailableOnDate(doctor, new Date(`${filters.date}T12:00:00`))) return false
  return true
}

export function availabilityLabel(doctor, today, english) {
  const offset = nextDay(doctor, today)
  if (offset === 0) return english ? 'Available today' : 'Disponible hoy'
  if (offset === 1) return english ? 'Consults tomorrow' : 'Consulta mañana'
  const day = new Date(today)
  day.setDate(day.getDate() + offset)
  const weekday = day.toLocaleDateString(english ? 'en-US' : 'es-DO', { weekday: 'long' })
  return `${english ? 'Consults' : 'Consulta'}: ${weekday}`
}
