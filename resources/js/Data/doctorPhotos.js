import photo01 from '../Assets/images/doctors/doctor-01.jpg'
import photo02 from '../Assets/images/doctors/doctor-02.jpg'
import photo03 from '../Assets/images/doctors/doctor-03.jpg'
import photo04 from '../Assets/images/doctors/doctor-04.jpg'
import photo05 from '../Assets/images/doctors/doctor-05.jpg'
import photo06 from '../Assets/images/doctors/doctor-06.jpg'
import photo07 from '../Assets/images/doctors/doctor-07.jpg'
import photo08 from '../Assets/images/doctors/doctor-08.jpg'

const photos = [photo01, photo02, photo03, photo04, photo05, photo06, photo07, photo08]

export function resolveDoctorPhoto(id, fallbackIndex = 0) {
  const index = Number.isFinite(Number(id)) ? Math.max(Number(id) - 1, 0) : fallbackIndex

  return photos[index % photos.length]
}
