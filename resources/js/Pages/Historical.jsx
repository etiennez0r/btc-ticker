import { Link } from '@inertiajs/inertia-react'
import React from 'react'

const Home = () => {
  return (
    <div>
      <Link href="/">Live Ticker</Link>
      <Link href="/historical" className="ml-2">Historical</Link>
    </div>
  )
}

export default Home