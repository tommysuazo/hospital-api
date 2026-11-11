// Static doctor directory for Centro Médico Oriental (no database).
// Availability is modelled as relative day offsets from "today" so that the
// "Disponible hoy / mañana / próximamente" indicators stay correct over time.

export const SPECIALTIES = [
  "Pediatría",
  "Cardiología",
  "Ginecología",
  "Dermatología",
  "Gastroenterología",
  "Obstetricia",
  "Oncología",
];

const photo = (id) =>
  `https://images.unsplash.com/photo-${id}?auto=format&fit=crop&w=640&q=80`;

export const DOCTORS = [
  {
    id: "maria-rodriguez",
    name: "Dra. María Rodríguez",
    specialties: ["Cardiología", "Medicina Interna"],
    photo: photo("1559839734-2b71ea197ec2"),
    consultorio: "204",
    extension: "1204",
    offsets: [0, 1, 4],
    bio: "Cardióloga con más de 15 años de experiencia en el diagnóstico y tratamiento de enfermedades del corazón. Especializada en hipertensión arterial, arritmias y prevención cardiovascular.",
  },
  {
    id: "andres-ferrer",
    name: "Dr. Andrés Ferrer",
    specialties: ["Pediatría"],
    photo: photo("1612349317150-e413f6a5b16d"),
    consultorio: "110",
    extension: "1110",
    offsets: [0, 2, 5],
    bio: "Pediatra dedicado al cuidado integral de la salud infantil, desde el recién nacido hasta el adolescente. Enfocado en crecimiento, vacunación y atención temprana de enfermedades comunes.",
  },
  {
    id: "daniela-ortiz",
    name: "Dra. Daniela Ortiz",
    specialties: ["Ginecología", "Obstetricia"],
    photo: photo("1594824476967-48c8b964273f"),
    consultorio: "318",
    extension: "1318",
    offsets: [1, 3, 6],
    bio: "Ginecóloga y obstetra con amplio experiencia en control prenatal, salud reproductiva y menopausia. Brinda atención empática y cercana a la mujer en cada etapa de su vida.",
  },
  {
    id: "ricardo-mota",
    name: "Dr. Ricardo Mota",
    specialties: ["Gastroenterología"],
    photo: photo("1622253692010-333f2da6031a"),
    consultorio: "225",
    extension: "1225",
    offsets: [0, 3, 7],
    bio: "Gastroenterólogo especializado en enfermedades del aparato digestivo, endoscopia digestiva y manejo de patologías como reflujo, úlceras y síndrome de intestino irritable.",
  },
  {
    id: "sofia-vega",
    name: "Dra. Sofía Vega",
    specialties: ["Dermatología"],
    photo: photo("1438761681033-6461ffad8d80"),
    consultorio: "142",
    extension: "1142",
    offsets: [1, 2, 8],
    bio: "Dermatóloga con experiencia en el diagnóstico y tratamiento de enfermedades de la piel, cyno y cabello. Ofrece evaluación de lunares, acne y prevención del cáncer de piel.",
  },
  {
    id: "elena-castillo",
    name: "Dra. Elena Castillo",
    specialties: ["Obstetricia", "Ginecología"],
    photo: photo("1573496359142-b8d87714a148"),
    consultorio: "320",
    extension: "1320",
    offsets: [0, 4],
    bio: "Obstetra comprometida con un embarazo seguro y sereno. Acompaña a las familias durante el embarazo, el parto y el posparto con calidez y rigor profesional.",
  },
  {
    id: "pablo-rios",
    name: "Dr. Pablo Ríos",
    specialties: ["Oncología"],
    photo: photo("1568602471122-7832951cc4c5"),
    consultorio: "412",
    extension: "1412",
    offsets: [2, 5, 9],
    bio: "Oncólogo clínico con dedicación al tratamiento integral y humano del paciente con cáncer. Trabaja en equipo multidisciplinario para ofrecer el mejor plan terapéutico.",
  },
  {
    id: "carolina-mendez",
    name: "Dra. Carolina Méndez",
    specialties: ["Pediatría"],
    photo: photo("1544005313-94ddf0286df2"),
    consultorio: "118",
    extension: "1118",
    offsets: [0, 1, 6],
    bio: "Pediatra con vocación por la prevención y la educación en salud. Atiende consultas de crecimiento, nutrición y enfermedades agudas con un trato cercano a las familias.",
  },
];

export function getDoctorById(id) {
  return DOCTORS.find((d) => d.id === id);
}

export function addDays(date, n) {
  const d = new Date(date);
  d.setDate(d.getDate() + n);
  return d;
}

export function toISO(d) {
  const y = d.getFullYear();
  const m = String(d.getMonth() + 1).padStart(2, "0");
  const day = String(d.getDate()).padStart(2, "0");
  return `${y}-${m}-${day}`;
}

function formatEsDate(d) {
  return d.toLocaleDateString("es-ES", { day: "numeric", month: "long" });
}

// { tone: "today" | "tomorrow" | "later", text }
export function availabilityLabel(doctor, today = new Date()) {
  const offs = doctor.offsets || [];
  if (offs.includes(0)) return { tone: "today", text: "Disponible hoy" };
  if (offs.includes(1)) return { tone: "tomorrow", text: "Disponible mañana" };
  const future = offs.filter((x) => x > 0).sort((a, b) => a - b);
  if (future.length === 0) return { tone: "later", text: "Sin disponibilidad próxima" };
  return { tone: "later", text: `Próxima disponibilidad: ${formatEsDate(addDays(today, future[0]))}` };
}

export function doctorAvailableDates(doctor, today = new Date()) {
  return (doctor.offsets || []).map((o) => toISO(addDays(today, o)));
}

export function isAvailableOn(doctor, isoDate, today = new Date()) {
  if (!isoDate) return true;
  return doctorAvailableDates(doctor, today).includes(isoDate);
}