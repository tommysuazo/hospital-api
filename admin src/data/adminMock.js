// Realistic placeholder data for the administrative portal.

const pic = (id) => `https://images.unsplash.com/photo-${id}?auto=format&fit=crop&w=160&q=80`;

export const MEDICOS = [
  { id: "m1", name: "Dra. María Rodríguez", specialties: ["Cardiología", "Medicina Interna"], photo: pic("1559839734-2b71ea197ec2"), office: "204", extension: "1204", branch: "Centro Médico Principal", availability: "Disponible", status: "Activo" },
  { id: "m2", name: "Dr. Andrés Ferrer", specialties: ["Pediatría"], photo: pic("1612349317150-e413f6a5b16d"), office: "110", extension: "1110", branch: "Centro Médico Principal", availability: "Ocupado", status: "Activo" },
  { id: "m3", name: "Dra. Daniela Ortiz", specialties: ["Ginecología", "Obstetricia"], photo: pic("1594824476967-48c8b964273f"), office: "318", extension: "1318", branch: "Centro Médico Principal", availability: "Disponible", status: "Activo" },
  { id: "m4", name: "Dr. Ricardo Mota", specialties: ["Gastroenterología"], photo: pic("1622253692010-333f2da6031a"), office: "225", extension: "1225", branch: "Sucursal Norte", availability: "Ausente", status: "Activo" },
  { id: "m5", name: "Dra. Sofía Vega", specialties: ["Dermatología"], photo: pic("1438761681033-6461ffad8d80"), office: "142", extension: "1142", branch: "Centro Médico Principal", availability: "Disponible", status: "Activo" },
  { id: "m6", name: "Dra. Elena Castillo", specialties: ["Obstetricia", "Ginecología"], photo: pic("1573496359142-b8d87714a148"), office: "320", extension: "1320", branch: "Sucursal Norte", availability: "Disponible", status: "Inactivo" },
  { id: "m7", name: "Dr. Pablo Ríos", specialties: ["Oncología"], photo: pic("1568602471122-7832951cc4c5"), office: "412", extension: "1412", branch: "Centro Médico Principal", availability: "Ocupado", status: "Activo" },
  { id: "m8", name: "Dra. Carolina Méndez", specialties: ["Pediatría"], photo: pic("1544005313-94ddf0286df2"), office: "118", extension: "1118", branch: "Centro Médico Principal", availability: "Disponible", status: "Activo" },
];

export const PACIENTES = [
  { id: "p1", name: "Laura Gómez", document: "001-1234567-8", phone: "+1 809 555 0101", email: "laura.gomez@mail.com", sexo: "Femenino", dob: "1989-04-12", seguro: "ARS Humano · Plan Básico", ultimaCita: "2026-08-18", proximaCita: "2026-08-27", status: "Activo" },
  { id: "p2", name: "Carlos Peréz", document: "402-7654321-2", phone: "+1 809 555 0102", email: "carlos.perez@mail.com", sexo: "Masculino", dob: "1975-11-03", seguro: "Mapfre · Plan Familiar", ultimaCita: "2026-08-09", proximaCita: "—", status: "Activo" },
  { id: "p3", name: "Marta Suero", document: "001-9988776-5", phone: "+1 809 555 0103", email: "marta.suero@mail.com", sexo: "Femenino", dob: "1994-02-20", seguro: "ARS Palic · Premium", ultimaCita: "2026-08-21", proximaCita: "2026-08-28", status: "Activo" },
  { id: "p4", name: "Guillermo Núñez", document: "003-1122334-1", phone: "+1 809 555 0104", email: "guillermo.nunez@mail.com", sexo: "Masculino", dob: "1968-07-30", seguro: "Sin seguro", ultimaCita: "2026-07-30", proximaCita: "—", status: "Inactivo" },
  { id: "p5", name: "Rosa Delgado", document: "001-5544332-9", phone: "+1 809 555 0105", email: "rosa.delgado@mail.com", sexo: "Femenino", dob: "2001-09-15", seguro: "ARS Humano · Plan Estudiantil", ultimaCita: "2026-08-23", proximaCita: "2026-08-29", status: "Activo" },
  { id: "p6", name: "Eduardo Beltré", document: "402-3344556-7", phone: "+1 809 555 0106", email: "eduardo.beltré@mail.com", sexo: "Masculino", dob: "1983-01-08", seguro: "Mapfre · Plan Ejecutivo", ultimaCita: "2026-08-12", proximaCita: "—", status: "Activo" },
];

export const CITAS = [
  { id: "c1", paciente: "Laura Gómez", medico: "Dra. María Rodríguez", especialidad: "Cardiología", fecha: "2026-08-27", hora: "09:00", consultorio: "204", estado: "Confirmada", sucursal: "Centro Médico Principal" },
  { id: "c2", paciente: "Carlos Peréz", medico: "Dr. Andrés Ferrer", especialidad: "Pediatría", fecha: "2026-08-27", hora: "09:30", consultorio: "110", estado: "Pendiente", sucursal: "Centro Médico Principal" },
  { id: "c3", paciente: "Marta Suero", medico: "Dra. Daniela Ortiz", especialidad: "Ginecología", fecha: "2026-08-27", hora: "10:15", consultorio: "318", estado: "En espera", sucursal: "Centro Médico Principal" },
  { id: "c4", paciente: "Rosa Delgado", medico: "Dra. Sofía Vega", especialidad: "Dermatología", fecha: "2026-08-27", hora: "11:00", consultorio: "142", estado: "En consulta", sucursal: "Centro Médico Principal" },
  { id: "c5", paciente: "Eduardo Beltré", medico: "Dr. Ricardo Mota", especialidad: "Gastroenterología", fecha: "2026-08-26", hora: "14:30", consultorio: "225", estado: "Completada", sucursal: "Sucursal Norte" },
  { id: "c6", paciente: "Guillermo Núñez", medico: "Dr. Pablo Ríos", especialidad: "Oncología", fecha: "2026-08-25", hora: "08:45", consultorio: "412", estado: "Cancelada", sucursal: "Centro Médico Principal" },
  { id: "c7", paciente: "Carlos Peréz", medico: "Dr. Andrés Ferrer", especialidad: "Pediatría", fecha: "2026-08-24", hora: "16:00", consultorio: "110", estado: "No asistió", sucursal: "Centro Médico Principal" },
  { id: "c8", paciente: "Laura Gómez", medico: "Dra. María Rodríguez", especialidad: "Cardiología", fecha: "2026-08-27", hora: "12:30", consultorio: "204", estado: "Pendiente", sucursal: "Centro Médico Principal" },
];

export const ESPECIALIDADES = [
  { id: "e1", name: "Pediatría", doctores: 2, servicios: 3, status: "Activo" },
  { id: "e2", name: "Cardiología", doctores: 1, servicios: 4, status: "Activo" },
  { id: "e3", name: "Ginecología", doctores: 2, servicios: 3, status: "Activo" },
  { id: "e4", name: "Dermatología", doctores: 1, servicios: 2, status: "Activo" },
  { id: "e5", name: "Gastroenterología", doctores: 1, servicios: 3, status: "Activo" },
  { id: "e6", name: "Obstetricia", doctores: 2, servicios: 2, status: "Activo" },
  { id: "e7", name: "Oncología", doctores: 1, servicios: 3, status: "Inactivo" },
];

export const SERVICIOS = [
  { id: "s1", name: "Consulta pediátrica", especialidad: "Pediatría", duracion: 30, precio: 2500, doctores: ["Dr. Andrés Ferrer", "Dra. Carolina Méndez"], status: "Activo" },
  { id: "s2", name: "Electrocardiograma", especialidad: "Cardiología", duracion: 20, precio: 1800, doctores: ["Dra. María Rodríguez"], status: "Activo" },
  { id: "s3", name: "Ecografía obstétrica", especialidad: "Obstetricia", duracion: 45, precio: 3200, doctores: ["Dra. Daniela Ortiz", "Dra. Elena Castillo"], status: "Activo" },
  { id: "s4", name: "Consulta dermatológica", especialidad: "Dermatología", duracion: 30, precio: 2200, doctores: ["Dra. Sofía Vega"], status: "Activo" },
  { id: "s5", name: "Endoscopia digestiva", especialidad: "Gastroenterología", duracion: 60, precio: null, doctores: ["Dr. Ricardo Mota"], status: "Activo" },
  { id: "s6", name: "Sesión de quimioterapia", especialidad: "Oncología", duracion: 120, precio: null, doctores: ["Dr. Pablo Ríos"], status: "Inactivo" },
];

export const RESULTADOS = [
  { id: "r1", paciente: "Laura Gómez", tipo: "Electrocardiograma", medico: "Dra. María Rodríguez", fecha: "2026-08-26", cita: "c1", estado: "Pendiente" },
  { id: "r2", paciente: "Marta Suero", tipo: "Biometría hemática", medico: "Dra. Daniela Ortiz", fecha: "2026-08-25", cita: "—", estado: "Borrador" },
  { id: "r3", paciente: "Eduardo Beltré", tipo: "Endoscopia informe", medico: "Dr. Ricardo Mota", fecha: "2026-08-26", cita: "c5", estado: "Publicado" },
  { id: "r4", paciente: "Rosa Delgado", tipo: "Biopsia de piel", medico: "Dra. Sofía Vega", fecha: "2026-08-24", cita: "—", estado: "Publicado" },
  { id: "r5", paciente: "Carlos Peréz", tipo: "Examen pediátrico", medico: "Dr. Andrés Ferrer", fecha: "2026-08-27", cita: "c2", estado: "Pendiente" },
];

export const DOCUMENTOS = [
  { id: "d1", name: "Carnet identificación Laura.pdf", paciente: "Laura Gómez", categoria: "Identificación", fecha: "2026-08-18", subidoPor: "Recepción" },
  { id: "d2", name: "Tarjeta Humano Carlos.pdf", paciente: "Carlos Peréz", categoria: "Tarjetas de seguro", fecha: "2026-08-09", subidoPor: "Recepción" },
  { id: "d3", name: "Consentimiento endoscopia.pdf", paciente: "Eduardo Beltré", categoria: "Consentimientos", fecha: "2026-08-26", subidoPor: "Dra. M. Rodríguez" },
  { id: "d4", name: "Orden ecografía.pdf", paciente: "Marta Suero", categoria: "Órdenes médicas", fecha: "2026-08-21", subidoPor: "Dra. D. Ortiz" },
  { id: "d5", name: "Autorización biopsia.pdf", paciente: "Rosa Delgado", categoria: "Autorizaciones", fecha: "2026-08-24", subidoPor: "Recepción" },
];

export const PERSONAL = [
  { id: "u1", usuario: "Admin General", correo: "admin@cmo.do", rol: "Administrador", medico: "—", ultimoAcceso: "2026-08-27 08:02", status: "Activo" },
  { id: "u2", usuario: "RecepciónUno", correo: "recepcion@cmo.do", rol: "Recepcionista", medico: "—", ultimoAcceso: "2026-08-26 17:40", status: "Activo" },
  { id: "u3", usuario: "Dra. María Rodríguez", correo: "mrodriguez@cmo.do", rol: "Médico", medico: "Dra. María Rodríguez", ultimoAcceso: "2026-08-27 07:55", status: "Activo" },
  { id: "u4", usuario: "LabTecUno", correo: "lab@cmo.do", rol: "Laboratorio", medico: "—", ultimoAcceso: "2026-08-25 14:20", status: "Activo" },
  { id: "u5", usuario: "GestionPac", correo: "gestion@cmo.do", rol: "Gestión de pacientes", medico: "—", ultimoAcceso: "2026-08-20 11:10", status: "Inactivo" },
];

export const AUDIT = [
  { id: "a1", fecha: "2026-08-27 08:05", usuario: "Admin General", accion: "Modificó una cita", modulo: "Citas", registro: "Cita #c1", ip: "190.8.x.x" },
  { id: "a2", fecha: "2026-08-27 09:30", usuario: "Dra. María Rodríguez", accion: "Publicó un resultado", modulo: "Resultados médicos", registro: "Resultado #r3", ip: "190.8.x.x" },
  { id: "a3", fecha: "2026-08-26 16:12", usuario: "Admin General", accion: "Cambió un rol", modulo: "Roles y permisos", registro: "Usuario u5", ip: "190.8.x.x" },
  { id: "a4", fecha: "2026-08-26 11:45", usuario: "RecepciónUno", accion: "Editó un paciente", modulo: "Pacientes", registro: "Paciente p2", ip: "190.8.x.x" },
  { id: "a5", fecha: "2026-08-25 10:20", usuario: "LabTecUno", accion: "Creó un documento", modulo: "Documentos", registro: "Documento d4", ip: "190.8.x.x" },
];

export const ASEGURADORAS = [
  { id: "aseg1", name: "ARS Humano", telefono: "+1 809 555 2000", estado: "Activo", planes: 3 },
  { id: "aseg2", name: "Mapfre Salud", telefono: "+1 809 555 2010", estado: "Activo", planes: 4 },
  { id: "aseg3", name: "ARS Palic", telefono: "+1 809 555 2020", estado: "Activo", planes: 2 },
];

export const PLANES = [
  { id: "pl1", aseguradora: "ARS Humano", plan: "Básico", cobertura: "60%", estado: "Activo" },
  { id: "pl2", aseguradora: "ARS Humano", plan: "Estudiantil", cobertura: "50%", estado: "Activo" },
  { id: "pl3", aseguradora: "Mapfre Salud", plan: "Familiar", cobertura: "75%", estado: "Activo" },
  { id: "pl4", aseguradora: "Mapfre Salud", plan: "Ejecutivo", cobertura: "85%", estado: "Activo" },
  { id: "pl5", aseguradora: "ARS Palic", plan: "Premium", cobertura: "90%", estado: "Activo" },
];

export const PACIENTES_ASEGURADOS = [
  { id: "pa1", paciente: "Laura Gómez", aseguradora: "ARS Humano", plan: "Básico", afiliado: "HUM-102030", titular: "Laura Gómez", inicio: "2025-01-15", vencimiento: "2026-12-31", estado: "Activo" },
  { id: "pa2", paciente: "Carlos Peréz", aseguradora: "Mapfre Salud", plan: "Familiar", afiliado: "MAP-445566", titular: "Carlos Peréz", inicio: "2024-09-01", vencimiento: "2026-12-31", estado: "Activo" },
  { id: "pa3", paciente: "Marta Suero", aseguradora: "ARS Palic", plan: "Premium", afiliado: "PAL-778899", titular: "Marta Suero", inicio: "2025-03-10", vencimiento: "2026-10-10", estado: "Activo" },
  { id: "pa4", paciente: "Rosa Delgado", aseguradora: "ARS Humano", plan: "Estudiantil", afiliado: "HUM-556677", titular: "Rosa Delgado", inicio: "2025-08-01", vencimiento: "2026-08-01", estado: "Vencido" },
];

// Instalaciones (jerarquía)
export const SUCURSALES = [
  {
    id: "suc1", nombre: "Centro Médico Principal", direccion: "Av. 27 de Febrero #100, Santiago",
    pisos: [
      { id: "p1", nombre: "Piso 1", departamentos: [
        { id: "d1", nombre: "Emergencias", consultorios: [{ id: "co1", numero: "EM-01", piso: "Piso 1", departamento: "Emergencias", extension: "1000", estado: "Activo", medico: "Turno médico" }] },
        { id: "d2", nombre: "Laboratorio", consultorios: [{ id: "co2", numero: "LAB-01", piso: "Piso 1", departamento: "Laboratorio", extension: "1005", estado: "Activo", medico: "Lab. Clínico" }] },
      ] },
      { id: "p2", nombre: "Piso 2", departamentos: [
        { id: "d3", nombre: "Pediatría", consultorios: [
          { id: "co3", numero: "110", piso: "Piso 2", departamento: "Pediatría", extension: "1110", estado: "Activo", medico: "Dr. Andrés Ferrer" },
          { id: "co4", numero: "118", piso: "Piso 2", departamento: "Pediatría", extension: "1118", estado: "Activo", medico: "Dra. Carolina Méndez" },
        ] },
      ] },
      { id: "p3", nombre: "Piso 3", departamentos: [
        { id: "d4", nombre: "Cardiología", consultorios: [{ id: "co5", numero: "204", piso: "Piso 3", departamento: "Cardiología", extension: "1204", estado: "Activo", medico: "Dra. María Rodríguez" }] },
        { id: "d5", nombre: "Ginecología", consultorios: [{ id: "co6", numero: "318", piso: "Piso 3", departamento: "Ginecología", extension: "1318", estado: "Activo", medico: "Dra. Daniela Ortiz" }] },
      ] },
    ],
  },
  { id: "suc2", nombre: "Sucursal Norte", direccion: "Calle Duarte #50, Moca", pisos: [
    { id: "p4", nombre: "Piso 1", departamentos: [
      { id: "d6", nombre: "Gastroenterología", consultorios: [{ id: "co7", numero: "225", piso: "Piso 1", departamento: "Gastroenterología", extension: "1225", estado: "Mantenimiento", medico: "Dr. Ricardo Mota" }] },
      { id: "d7", nombre: "Obstetricia", consultorios: [{ id: "co8", numero: "320", piso: "Piso 1", departamento: "Obstetricia", extension: "1320", estado: "Activo", medico: "Dra. Elena Castillo" }] },
    ] },
  ] },
];

// Dashboard data
export const KPI = [
  { key: "citasHoy", label: "Citas de hoy", value: 24, tone: "brand", sub: "+3 vs ayer" },
  { key: "pendientes", label: "Citas pendientes", value: 7, tone: "amber" },
  { key: "atendidos", label: "Pacientes atendidos", value: 18, tone: "green" },
  { key: "medicosDisp", label: "Médicos disponibles", value: 5, tone: "sky" },
  { key: "resultados", label: "Resultados pendientes", value: 2, tone: "violet" },
  { key: "alertas", label: "Alertas / incidencias", value: 1, tone: "rose" },
];

export const WEEKLY_CITAS = [
  { dia: "Lun", citas: 18 }, { dia: "Mar", citas: 22 }, { dia: "Mié", citas: 20 },
  { dia: "Jue", citas: 26 }, { dia: "Vie", citas: 24 }, { dia: "Sáb", citas: 12 }, { dia: "Dom", citas: 4 },
];

export const STATUS_DISTRIBUTION = [
  { name: "Completadas", value: 42, color: "#2BAE78" },
  { name: "Confirmadas", value: 28, color: "#274481" },
  { name: "Pendientes", value: 14, color: "#f59e0b" },
  { name: "Canceladas", value: 9, color: "#f43f5e" },
  { name: "No asistió", value: 7, color: "#94a3b8" },
];

export const ACTIVIDAD = [
  { id: "ac1", texto: "Dra. María Rodríguez confirmó una cita de Laura Gómez", hora: "Hace 12 min", tone: "green" },
  { id: "ac2", texto: "Laboratorio subió un resultado para Eduardo Beltré", hora: "Hace 38 min", tone: "sky" },
  { id: "ac3", texto: "Recepción registró al paciente Rosa Delgado", hora: "Hace 1 h", tone: "brand" },
  { id: "ac4", texto: "Sistema: consultorio 225 en mantenimiento", hora: "Hace 2 h", tone: "amber" },
];

export const MEDICOS_DISPONIBLES = [
  { name: "Dra. María Rodríguez", specialty: "Cardiología", office: "204", nextSlot: "09:00" },
  { name: "Dra. Sofía Vega", specialty: "Dermatología", office: "142", nextSlot: "11:00" },
  { name: "Dra. Carolina Méndez", specialty: "Pediatría", office: "118", nextSlot: "12:00" },
  { name: "Dr. Andrés Ferrer", specialty: "Pediatría", office: "110", nextSlot: "—" },
];

// Horarios grid helpers
export const DIAS = ["Lun", "Mar", "Mié", "Jue", "Vie", "Sáb", "Dom"];
export const FRANJAS = ["08:00", "09:00", "10:00", "11:00", "12:00", "14:00", "15:00", "16:00"];

// filas de horario por médico: { medico, slots: { 'Lun-08:00': 'Disponible'|'Cita'|'Ocupado'|'Bloqueado'|'Ausente' } }
export const HORARIOS = MEDICOS.slice(0, 6).map((m, i) => {
  const slots = {};
  DIAS.forEach((d) => {
    FRANJAS.forEach((f) => {
      const key = `${d}-${f}`;
      slots[key] = i % 2 === 0 ? "Disponible" : "Ocupado";
    });
  });
  // some specific blocks
  slots["Lun-09:00"] = "Cita";
  slots["Mar-10:00"] = "Cita";
  slots["Jue-14:00"] = "Bloqueado";
  if (i === 2) DIAS.forEach((d) => (slots[`${d}-16:00`] = "Ausente"));
  return { medico: m.name, office: m.office, slots };
});