import React, { useState } from "react";
import Navbar from "@/components/site/Navbar";
import Hero from "@/components/site/Hero";
import Specialties from "@/components/site/Specialties";
import Doctors from "@/components/site/Doctors";
import About from "@/components/site/About";
import Location from "@/components/site/Location";
import Footer from "@/components/site/Footer";
import BookingDialog from "@/components/site/BookingDialog";

export default function Home() {
  const [bookingOpen, setBookingOpen] = useState(false);
  const openBooking = () => setBookingOpen(true);

  return (
    <div className="bg-white text-ink font-body antialiased overflow-x-hidden">
      <Navbar />
      <main>
        <Hero onBook={openBooking} />
        <Specialties />
        <Doctors />
        <About />
        <Location />
      </main>
      <Footer onBook={openBooking} />
      <BookingDialog open={bookingOpen} onOpenChange={setBookingOpen} />
    </div>
  );
}