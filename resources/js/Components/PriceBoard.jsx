import React from 'react'

const PriceBoard = (props) => {
  return (
    <div className="grid grid-cols-2 priceboard">
        <div className="text-right">
            {props.symbol}:
        </div>
        <div className={props.className}>
            {props.price * 1}
        </div>
        <div className="text-right">
            Variation:
        </div>
        <div className={props.className}>
            {props.variation}
        </div>
        <div className="text-right">
            Gains:
        </div>
        <div className={props.className}>
            {props.gains}%
        </div>
    </div>
  )
}

export default PriceBoard