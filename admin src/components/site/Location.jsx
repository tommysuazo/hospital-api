import React from "react";
import { MapContainer, TileLayer, Marker, Popup } from "react-leaflet";
import L from "leaflet";
import "leaflet/dist/leaflet.css";
import { MapPin, Phone, MessageCircle, Mail, Navigation } from "lucide-react";

const POSITION = [18.4861, -69.9312];

const clinicIcon = L.icon({
  iconUrl: "https://cdn.jsdelivr.net/npm/leaflet@1.9.4/dist/images/marker-icon.png",
  iconRetinaUrl: "https://cdn.jsdelivr.net/npm/leaflet@1.9.4/dist/images/marker-icon-2x.png",
  shadowUrl: "https://cdn.jsdelivr.net/npm/leaflet@1.9.4/dist/images/marker-shadow.png",
  iconSize: [25, 41],
  iconAnchor: [12, 41],
  popupAnchor: [1, -34],
});

const DIRECTIONS_URL = "https://www.google.com/maps/dir/?api=1&destination=18.4861,-69.9312";

export default function Location() {
  return (
    <section id="contacto" className="py-20 md:py-28 bg-canvas scroll-mt-24">
      <div className="max-w-7xl mx-auto px-6 lg:px-8">
        <div className="max-w-2xl mb-12">
          <span className="inline-block text-[13px] font-semibold tracking-[0.2em] uppercase text-vitagreen mb-3">
            Encuéntranos
          </span>
          <h2 className="font-heading text-3xl md:text-4xl lg:text-[2.75rem] leading-[1.1] font-extrabold text-brand tracking-tight">
            Te esperamos en nuestro centro
          </h2>
          <p className="mt-4 text-ink/70 text-lg leading-relaxed">
            Estamos ubicados en un punto de fácil acceso. Ven a visitarnos o contáctanos por cualquier medio.
          </p>
        </div>

        <div className="grid lg:grid-cols-5 gap-6 lg:gap-8 items-stretch">
          <div className="lg:col-span-3 rounded-3xl overflow-hidden border border-slate-200 shadow-[0_18px_50px_-28px_rgba(39,68,129,0.4)] h-[360px] lg:h-full lg:min-h-[460px]">
            <MapContainer center={POSITION} zoom={15} scrollWheelZoom={false} style={{ height: "100%", width: "100%" }}>
              <TileLayer
                url="https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png"
                attribution='&copy; OpenStreetMap &copy; CARTO'
              />
              <Marker position={POSITION} icon={clinicIcon}>
                <Popup>
                  <strong>Centro Médico Oriental</strong>
                  <br />
                  Av. Integración #45, Santo Domingo
                </Popup>
              </Marker>
            </MapContainer>
          </div>

          <div className="lg:col-span-2 rounded-3xl bg-brand text-white p-8 md:p-10 shadow-[0_18px_50px_-25px_rgba(39,68,129,0.6)] flex flex-col">
            <h3 className="font-heading text-2xl font-bold">Información de contacto</h3>
            <p className="mt-2 text-white/70 leading-relaxed">Siempre listos para atenderte.</p>

            <ul className="mt-8 space-y-6 flex-1">
              <li className="flex items-start gap-4">
                <span className="grid place-items-center w-11 h-11 rounded-xl bg-white/10 text-vitagreen shrink-0">
                  <MapPin className="w-5 h-5" />
                </span>
                <div>
                  <div className="text-sm text-white/60">Dirección</div>
                  <div className="font-medium">Av. Integración #45, Santo Domingo, República Dominicana</div>
                </div>
              </li>
              <li className="flex items-start gap-4">
                <span className="grid place-items-center w-11 h-11 rounded-xl bg-white/10 text-vitagreen shrink-0">
                  <Phone className="w-5 h-5" />
                </span>
                <div>
                  <div className="text-sm text-white/60">Teléfono</div>
                  <div className="font-medium">+1 (809) 555-0123</div>
                </div>
              </li>
              <li className="flex items-start gap-4">
                <span className="grid place-items-center w-11 h-11 rounded-xl bg-white/10 text-vitagreen shrink-0">
                  <MessageCircle className="w-5 h-5" />
                </span>
                <div>
                  <div className="text-sm text-white/60">WhatsApp</div>
                  <div className="font-medium">+1 (829) 555-0145</div>
                </div>
              </li>
              <li className="flex items-start gap-4">
                <span className="grid place-items-center w-11 h-11 rounded-xl bg-white/10 text-vitagreen shrink-0">
                  <Mail className="w-5 h-5" />
                </span>
                <div>
                  <div className="text-sm text-white/60">Correo electrónico</div>
                  <div className="font-medium">contacto@centromedicooriental.com</div>
                </div>
              </li>
            </ul>

            <a
              href={DIRECTIONS_URL}
              target="_blank"
              rel="noreferrer"
              className="mt-8 inline-flex items-center justify-center gap-2 h-13 px-6 py-3.5 rounded-full bg-vitagreen text-white font-semibold shadow-md shadow-vitagreen/20 hover:bg-[#209364] active:scale-[0.98] transition-all"
              style={{ minHeight: "48px" }}
            >
              <Navigation className="w-4 h-4" />
              Cómo llegar
            </a>
          </div>
        </div>
      </div>
    </section>
  );
}