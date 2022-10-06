import PriceBoard from '@/Components/PriceBoard'
import HomeLayout from '@/Layouts/HomeLayout'
import axios from 'axios'
import React, { useEffect, useState } from 'react'

const SYMBOL = 'BTC/USDT'
const API_ENDPOINT = `/api/v1/ticker?symbol=${SYMBOL}`

let oldTicker = {price: 0}

const Ticker = () => {
    const [ticker, setTicker] = useState(oldTicker)
    const [variation, setVariation] = useState(0)
    const [gains, setGains] = useState(0)
    const [className, setClassName] = useState('')

    // query function
    const fetchTicker = () => {
        axios.get(API_ENDPOINT)
            .then((result) => {
                setTicker(result.data.ticker)
            })
            .catch((error) => console.log(error))
    }

    // update after every query
    useEffect(() => {
        let variation = ticker.price - oldTicker.price // diferencia de precios
        let gains = 0

        variation = Math.round(variation * 100) / 100
        
        if (oldTicker.price) {
            gains = variation / oldTicker.price
            gains = Math.round(gains * 100000) / 100000
        }
        
        setVariation(variation)
        setGains(gains)
        if (gains > 0)
            setClassName('positive')
            
        if (gains < 0)
            setClassName('negative')
            
        document.title = `${SYMBOL}: ${ticker.price}`;

        oldTicker = ticker
      
    }, [ticker])
    
    // query timer..
    useEffect(() => {
        fetchTicker()

        const intervalId = setInterval(fetchTicker, 1000 * 10)
    
        return () => {
            clearInterval(intervalId)   // clear interval when unmount
        }
    }, [])
    
  return (
    <HomeLayout>
        <div className="content">
            <PriceBoard variation={variation} 
                        gains={gains}
                        symbol={SYMBOL}
                        price={ticker.price * 1}
                        className={className} />
        </div>
    </HomeLayout>
  )
}

export default Ticker