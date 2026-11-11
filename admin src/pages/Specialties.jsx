import React, { useState } from "react";
import Navbar from "@/components/site/Navbar";
import SpecialtiesSection from "@/components/site/Specialties";
import Footer from "@/components/site/Footer";
import BookingDialog from "@/components/site/BookingDialog";

export default function Specialties() {
  const [bookingOpen, setBookingOpen] = useState(false);
  const openBooking = () => setBookingOpen(true);

  return (
    <div className="bg-white text-ink font-body antialiased overflow-x-hidden">
      <Navbar />
      <main className="pt-[4.75rem]">
        <SpecialtiesSection />
      </main>
      <Footer onBook={openBooking} />
      <BookingDialog open={bookingOpen} onOpenChange={setBookingOpen} />
    </div>
  );
}