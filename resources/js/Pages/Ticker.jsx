import HomeLayout from '@/Layouts/HomeLayout'
import axios from 'axios'
import React, { useEffect, useState } from 'react'

const SYMBOL = 'BTC/USDT'
const API_ENDPOINT = `/api/v1/ticker?symbol=${SYMBOL}`

const Ticker = () => {
    const [ticker, setTicker] = useState({price: 0})
    const [variation, setVariation] = useState(0)
    const [gains, setGains] = useState(0)

    const fetchTicker = () => {
        axios.get(API_ENDPOINT)
            .then((result) => {
                const oldTicker = ticker
                let variation = result.data.ticker.price - oldTicker.price

                variation = Math.round(variation * 100) / 100

                setTicker(result.data.ticker);
                document.title = `${SYMBOL}: ${result.data.ticker.price}`;
                
                setVariation(variation)

                if (oldTicker.price) {
                    let gains = variation / oldTicker.price

                    gains = Math.round(gains * 100000) / 100000
                    setGains(gains)
                }
            })
            .catch((error) => console.log(error))
        console.log('consultando precio..')
    }

    useEffect(() => {
        fetchTicker()

        console.log('creando intervalo..')
        const intervalId = setInterval(fetchTicker, 1000 * 10)
    
        return () => {
            console.log('limpiando el intervalo..')
            clearInterval(intervalId)
        }
    }, [])
    
  return (
    <HomeLayout>
        <div className="content">
            <div>
                {SYMBOL}: {ticker.price * 1}
            </div>
            <div>
                Variation: {variation}
            </div>
            <div>
                Gains: {gains}%
            </div>
        </div>
    </HomeLayout>
  )
}

export default Ticker