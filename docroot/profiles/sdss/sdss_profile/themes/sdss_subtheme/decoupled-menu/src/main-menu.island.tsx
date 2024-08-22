import styled from "styled-components";
import { Variables } from "./components/variables";
import {useWebComponentEvents} from "./hooks/useWebComponentEvents";
import {createIslandWebComponent} from 'preact-island'
import {useState, useEffect, useRef, useCallback} from 'preact/hooks';
import {deserialize} from "./tools/deserialize";
import {buildMenuTree, MenuContentItem} from "./tools/build-menu-tree";
import {DRUPAL_DOMAIN} from './config/env'
import OutsideClickHandler from "./components/outside-click-handler";
import Caret from "./components/caret";
import Hamburger from "./components/hamburger";
import Close from "./components/close";
import MagnifyingGlass from "./components/magnifying-glass";
// import Logo from "./components/logo";

const islandName = 'main-menu-island'

// Which header?
// Blue = sdss-header-variant--option_a
// Green = no associated classname
const isBlueHeader = document.getElementsByClassName('sdss-header-variant--option_a');

// Set variable to Blue for default
var headerType = Variables.topBlue;
var leftZero = Variables.leftBlue;

// Change it to blue if it is blue
if (isBlueHeader.length === 0) {
  var headerType = Variables.topGreen;
  var leftZero = Variables.leftGreen;

}

const TopList = styled.ul<{ open?: boolean }>`
  display: ${props => props.open ? "block" : "none"};
  padding: ${props => props.open ? "225px 0 0 0" : "0"};
  top: ${props => props.open ? "-225px" : "0"};
  position: absolute;
  left: 0;
  flex-direction: column;
  flex-wrap: wrap;
  justify-content: flex-start;
  list-style: none;
  margin: 0;
  margin-left: calc(50% - 50vw);
  margin-right: calc(50% - 50vw);
  font-size: 18px;
  z-index: 1000;
  width: 100vw;
  height: 115vh;

  @media (min-width: 992px) {
    display: flex;
    flex-direction: row;
    justify-content: flex-start;
    background: transparent;
    padding: 0;
    position: unset;
    font-size: 18px;
    width: 100%;
    margin: 0 auto;
    height: unset;
  }
`

const MobileMenuButton = styled.button`
  position: absolute;
  top: -45px;
  right: 0;
  box-shadow: none;
  background: transparent;
  border: 0;
  border-bottom: 2px solid transparent;
  color: black;
  padding: 0;
  display: flex;
  flex-direction: column;
  align-items: center;
  font-size: 1.7rem;
  width: auto;
  z-index: 1001;

  &:hover, &:focus, &:active {
    border-bottom: 0;
    background: transparent;
    color: #2E2D29;
    box-shadow: none;
    outline: none;
  }

  @media (min-width: 992px) {
    display: none;
  }
`

const MobileMenuWrapper = styled.div<{ open?: boolean, level?: number }>`
  display: block;
  background-color: #ffffff;
  height: auto;
  position: absolute;
  top: 0;
  bottom: 0;
  width: 100vw;
  position: fixed;
  z-index: -1;

@media (min-width: 992px) {
  display: none;
}
`

const SearchContainer = styled.div`
  padding: 10px 30px 0;
  margin: 0;
  background: #ffffff;

  form {
    display: flex;
    flex-direction: column;
    gap: 10px;
    padding-bottom: 22px;
  }

  label {
    padding: 0 10px;
    margin: 0;
    color: #ffffff;
  }

  input {
    margin: 0;
    width: 100%;
    border-radius: 999px;
    height: 40px;
    padding: 0 20px;
    max-width: 100%;
  }

  button {
    position: absolute;
    top: 0;
    right: 0;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    background: none;
    color: #b1040e;
    border: 1px solid transparent;
    border-radius: 999px;
    aspect-ratio: 1;
    padding: 0;
    margin: 0;

    &:hover, &:focus {
      border: 1px solid #2e2d29;
    }
  }

  @media (min-width: 992px) {
    display: none;
  }
`

export const MainMenu = ({}) => {
  useWebComponentEvents(islandName)

  const [menuItems, setMenuItems] = useState<MenuContentItem[]>([]);
  const [menuOpen, setMenuOpen] = useState<boolean>(false);
  const buttonRef = useRef<HTMLButtonElement>(null);

  useEffect(() => {
    fetch(DRUPAL_DOMAIN + '/jsonapi/menu_items/main')
      .then(res => res.json())
      .then(data => setMenuItems(deserialize(data)))
      .catch(err => console.error(err));
  }, [])

  const handleEscape = useCallback((event: KeyboardEvent) => {
    if (event.key === "Escape" && menuOpen) {
      setMenuOpen(false);
      buttonRef.current.focus();
    }
  }, [menuOpen]);

  useEffect(() => {
    // Add keydown listener for escape button if the submenu is open.
    if (menuOpen) document.addEventListener("keydown", handleEscape);
    if (!menuOpen) document.removeEventListener("keydown", handleEscape);
  }, [menuOpen]);

  const menuTree = buildMenuTree(menuItems);
  if (!menuTree.items || menuTree.items?.length === 0) return <div/>;

  // Remove the default menu.
  const existingMenu = document.getElementsByClassName('su-multi-menu');
  if (existingMenu.length > 0) {
    existingMenu[0].remove();
  }

  return (
    <OutsideClickHandler
      component="div"
      onOutsideFocus={() => setMenuOpen(false)}
      className="menu-wrapper"
    >
      <Nav>
        {/* <Logo open={menuOpen}
        /> */}

        <MobileMenuButton ref={buttonRef} onClick={() => setMenuOpen(!menuOpen)} aria-expanded={menuOpen}>
          {menuOpen ? <Close /> : <Hamburger />}
          {menuOpen ? "Close" : ""}
        </MobileMenuButton>


        <TopList open={menuOpen}>
        <SearchContainer>
          <form action="/search" method="get">
            <label htmlFor="mobile-search-input">Keyword Search</label>
            <div style={{position: "relative"}}>
              <input
                id="mobile-search-input"
                type="text"
                placeholder="Search this site"
                name="key"
              />
              <button type="submit">
                <MagnifyingGlass style={{width: "25px", height: "25px"}}/>
                <span className="visually-hidden">Submit Search</span>
              </button>
            </div>
          </form>
        </SearchContainer>
          {menuTree.items.map(item => <MenuItem key={item.id} {...item} />)}
          <MobileMenuWrapper></MobileMenuWrapper>
        </TopList>
      </Nav>
    </OutsideClickHandler>
  )
}

const Mobile = styled.span`
  @media (min-width: 992px) {
    position: unset;
  }
`

const Nav = styled.nav`
  position: relative;
  width: 100%;

  @media (min-width: 992px) {
    position: unset;
  }
`

const Button = styled.button<{ open?: boolean }>`
  color: #2E2D29;
  background: transparent;
  border: none;
  padding: 0;
  margin: 0 10px 0 -4px;
  box-shadow: none;
  flex-shrink: 0;
  border-radius: 38px;
  transition: color 0.2s ease-in-out, background 0.2s ease-in-out, border 0.2s ease-in-out;
  width: 38px;
  height: 38px;

  &:focus, &:hover, &:active {
    box-shadow: none;
    border-bottom: none;
    border-radius: 38px;
    color: #ffffff;
    background-color: #014240;
    outline: none;
  }

  @media (min-width: 992px) {
    margin: 12px 10px 0 10px;

    &:hover, &:focus, &:active {

    }
  }
`

const MenuItemContainer = styled.div<{ open?: boolean, level?: number }>`
  background-color: ${props => props.open ? "#E9F7F8" : "transparent"};
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-right: ${props => props.level === 0 ? "32px" : "0"};
  width: 100%;

  @media (min-width: 992px) {
    color: ${props => props.open ? "#ffffff" : "#155f65"};
    background-color: ${props => props.open ? "#F5FEFF" : "transparent"};
    padding-bottom: ${props => props.level === 0 ? "0" : "0"};
    width: ${props => props.level === 0 ? "fit-content" : "100%"};
    padding-bottom: ${props => props.level === 0 ? "2rem" : "0"};
    border-radius: ${props => props.level === 0 ? "10px" : "0"};
    padding-left: 12px;
  }
`

const MenuLink = styled.a<{ isCurrent?: boolean, inTrail?: boolean, level?: number }>`
  color: #2E2D29;
  font-weight: 400;
  text-decoration: none;
  padding: ${({level}) => level != 0 ? "16px 0 16px 36px" : "16px 20px"};

  transition: all 0.2s ease-in-out;
  width: 100%;

  &:hover, &:focus {
    text-decoration: underline;
    color: #014240;
    background-color: #E9F7F8;
  }

  &:active {
    text-decoration: underline;
    color: #014240;
    background-color: #E9F7F8;
  }

  @media (min-width: 992px) {
    color: ${({level}) => level != 0 ? "#2E2D29" : "#2E2D29"};
    font-weight: 400;
    font-size: 1.9rem;
    padding: ${({level}) => level != 0 ? "0" : "16px 0"};
    margin-bottom: ${({level, inTrail, isCurrent}) => level === 0 ? (isCurrent ? "-6px" : (inTrail ? "-6px" : "-6px")) : ""};


    &:hover, &:focus {
      color: ${({level}) => level != 0 ? "#155F65" : "#2E2D29"};
      background-color: #F5FEFF;
      border-radius: 10px;
      padding: ${({level}) => level != 0 ? "16px" : "0"};
    }
  }
`

const NoLink = styled.h2<{  open?: boolean, level?: number }>`
  color: #2e2D29;
  font-weight: 600;
  font-size: 23px;
  text-decoration: none;
  padding: 16px 0 16px 16px;
  line-height: 2.3;

  @media (min-width: 992px) {
    color: ${props => props.open ? "#ffffff" : "#2e2D29"};
    padding: ${({level}) => level != 0 ? "0" : "16px 0"};
  }
`

const MenuList = styled.ul<{ open?: boolean, level?: number }>`
  display: ${props => props.open ? "block" : "none"};
  z-index: ${props => props.level + 1};
  list-style: none;
  padding: 19px 0 15px 0;
  margin: 0;
  min-width: 300px;
  background: #E9F7F8;

  @media (min-width: 992px) {
    display: ${props => props.open ? "" : "none"};
    box-shadow: ${props => props.level === 0 ? "0 10px 20px rgba(0,0,0,.15),0 6px 6px rgba(0,0,0,.2)" : ""};
    border-top: 2px solid #bed9db;
    position: ${props => props.level === 0 ? "absolute" : "relative"};
    background: #E9F7F8;
    width: 100vw;
    left: 0;
    color: $sdss-color-white;
    padding: 3.6rem 15% 5.8rem 15%;
    top:  ${headerType};
    columns: 4;
    column-gap: 40px;
    column-width: 200px;
  }
`

const MenuListWrapper = styled.div<{ open?: boolean, level?: number }>`

  @media (min-width: 992px) {
    display: flex;
    opacity: 1;
    position: absolute;
    visibility: visible;
    background: linear-gradient(180deg,rgba(0,0,0,.08) 0,transparent 12px);
    background-color: #fff;
    left: ${leftZero};
    width: 100%;
    z-index: 220;
    top: 84px;
  }
`

const ListItem = styled.li<{ level?: number }>`
  position: unset;
  border-top: ${props => props.level > 0 ? "1px solid transparent" : "1px solid #014240"};
  margin: 0;

  &:last-child {
    border-bottom: none;
  }

  @media (min-width: 992px) {
    border-top: ${props => props.level === 0 ? "none" : "none"};
    padding: ${props => props.level > 0 ? "0" : "0"};
  }
`

const MenuItem = ({title, url, items, level = 0}: { title: string, url: string, items?: MenuContentItem[], level?: number }) => {
  const buttonRef = useRef(null)
  const [submenuOpen, setSubmenuOpen] = useState(false)
  const basePath = window.location.protocol + "//" + window.location.host;
  let linkUrl = new URL(basePath);
  let isNoLink = true;
  let isCurrent, inTrail = false;

  if (url) {
    isNoLink = false;
    linkUrl = new URL(url.startsWith('/') ? `${basePath}${url}` : url);
    isCurrent = linkUrl.pathname === window.location.pathname;
    inTrail = url != '/' && window.location.pathname.startsWith(linkUrl.pathname) && !isCurrent;
  }

  const handleEscape = useCallback((event: KeyboardEvent) => {
    if (event.key === "Escape" && submenuOpen) {
      setSubmenuOpen(false);
      buttonRef.current.focus();
    }
  }, [submenuOpen]);

  useEffect(() => {
    // Add keydown listener for escape button if the submenu is open.
    if (submenuOpen) document.addEventListener("keydown", handleEscape);
    if (!submenuOpen) document.removeEventListener("keydown", handleEscape);
  }, [submenuOpen]);

  return (
    <OutsideClickHandler
      component="nav"
      onOutsideFocus={() => setSubmenuOpen(false)}
      component={ListItem}
      level={level}
    >

      <MenuItemContainer open={submenuOpen} level={level}>
        {!isNoLink &&
          <MenuLink
            href={url}
            aria-current={isCurrent ? "page" : undefined}
            level={level}
            isCurrent={isCurrent}
            inTrail={inTrail}
          >
            {title}
          </MenuLink>
        }
        {isNoLink &&
          <NoLink open={submenuOpen}>{title}</NoLink>
        }

        {items && level === 0  ? (
            <Button
              ref={buttonRef}
              onClick={() => setSubmenuOpen(!submenuOpen)}
              aria-expanded={submenuOpen}
              aria-label={(submenuOpen ? "Close" : "Open") + ` ${title} Submenu`}
            >
              <Caret style={{
                transform: submenuOpen ? "rotate(180deg)" : "",
                transition: "transform 0.2s ease-in-out",
                width: "16px",
              }}
              />
            </Button>
        ) : ""
        }
      </MenuItemContainer>

      {items &&

        <MenuListWrapper>
          <MenuList open={submenuOpen} level={level}>

            {items.map(item =>
              <MenuItem key={item.id} {...item} level={level + 1}/>
            )}

          </MenuList>
        </MenuListWrapper>
      }

    </OutsideClickHandler>

  )
}


const island = createIslandWebComponent(islandName, MainMenu)
island.render({
  selector: `[data-island="${islandName}"]`,
})
