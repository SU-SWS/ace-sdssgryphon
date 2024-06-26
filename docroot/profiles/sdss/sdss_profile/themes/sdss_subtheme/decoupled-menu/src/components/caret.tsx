const Caret = ({...props}) => {
  return (
    <svg role="presentation" width="24" height="24" viewBox="0 0 24 24" fill="none" {...props}>
      <path fill-rule="evenodd" clip-rule="evenodd" d="M23.4887 5.41566C24.1363 6.00024 24.1737 6.98449 23.5725 7.61404L13.1725 18.5029C12.8697 18.8199 12.4449 19 12 19C11.5551 19 11.1303 18.8199 10.8275 18.5029L0.427538 7.61404C-0.173746 6.98449 -0.136251 6.00024 0.511286 5.41566C1.15882 4.83108 2.17119 4.86753 2.77248 5.49708L12 15.1584L21.2275 5.49708C21.8288 4.86753 22.8412 4.83108 23.4887 5.41566Z" fill="currentColor"/>
    </svg>
  )
}
export default Caret;
