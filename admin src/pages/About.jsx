import React, { useState } from "react";
import Navbar from "@/components/site/Navbar";
import AboutSection from "@/components/site/About";
import Footer from "@/components/site/Footer";
import BookingDialog from "@/components/site/BookingDialog";

export default function About() {
  const [bookingOpen, setBookingOpen] = useState(false);
  const openBooking = () => setBookingOpen(true);

  return (
    <div className="bg-white text-ink font-body antialiased overflow-x-hidden">
      <Navbar />
      <main className="pt-[4.75rem]">
        <AboutSection />
      </main>
      <Footer onBook={openBooking} />
      <BookingDialog open={bookingOpen} onOpenChange={setBookingOpen} />
    </div>
  );
}