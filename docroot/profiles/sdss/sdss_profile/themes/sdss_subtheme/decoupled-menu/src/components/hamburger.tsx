const Hamburger = ({...props}) => {
  return (
    <svg width="30px" height="26px" viewBox="0 0 24 24" fill="none" aria-hidden={true} {...props}>
      <path d="M4 18L20 18" stroke="#000000" stroke-width="2" stroke-linecap="round"/>
      <path d="M4 12L20 12" stroke="#000000" stroke-width="2" stroke-linecap="round"/>
      <path d="M4 6L20 6" stroke="#000000" stroke-width="2" stroke-linecap="round"/>
    </svg>
  )
}
export default Hamburger;