const HeadingBar = ({...props}) => {
    return (
      <div class="heading-bar">
        <svg width="265" height="42" viewBox="0 0 265 42" fill="none" xmlns="http://www.w3.org/2000/svg">
          <mask id="path-1-outside-1_10651_91251" maskUnits="userSpaceOnUse" x="0" y="0" width="265" height="42" fill="black">
          <rect fill="white" width="265" height="42"/>
          <path d="M0 1H265V42H0V1Z"/>
          </mask>
          <path d="M0 2H265V0H0V2Z" fill="#C0C0BF" mask="url(#path-1-outside-1_10651_91251)"/>
          <rect y="1" width="46.75" height="3" rx="1.5" fill="#368187"/>
        </svg>
      </div>
    )
  }
  export default HeadingBar;
