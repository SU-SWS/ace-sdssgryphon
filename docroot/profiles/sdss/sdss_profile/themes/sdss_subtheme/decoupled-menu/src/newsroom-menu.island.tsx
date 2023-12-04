import styled from "styled-components";
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
import Logo from "./components/logo";

const islandName = 'newsroom-menu-island'

const TopList = styled.ul<{ open?: boolean }>`
  display: ${props => props.open ? "flex" : "none"};
  padding: ${props => props.open ? "225px 0 0 0" : "0"};
  top: ${props => props.open ? "-225px" : "0"};
  background: #155F65;
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
    justify-content: flex-end;
    background: transparent;
    padding: 0;
    position: unset;
    font-size: 18px;
    width: 100%;
    margin: 0 auto;
    height: unset;
  }
`

const MobileMenuHeading = styled.div<{ open?: boolean }>`
  color: ${props => props.open ? "#ffffff" : "#000000"};
  top: ${props => props.open ? "-50px" : "0"};
  font-size: 32px;
  margin-bottom: 18px;
  z-index: ${props => props.open ? "1001" : "unset"};
  position: relative;
  text-align: center;

  @media (min-width: 992px) {
    display: none;
  }
`

const MobileMenuButton = styled.button`
  position: absolute;
  top: -60px;
  right: -8px;
  box-shadow: none;
  background: transparent;
  border: 0;
  border-bottom: 2px solid transparent;
  color: #ffffff;
  padding: 0;
  display: flex;
  flex-direction: column;
  align-items: center;
  font-size: 1.7rem;
  width: auto;
  z-index: 1001;

  &:hover, &:focus, &:active {
    background: transparent;
    color: #ffffff;
    box-shadow: none;
    outline: none;
  }

  @media (min-width: 992px) {
    display: none;
  }
`

const MobileMenuWrapper = styled.div<{ open?: boolean, level?: number }>`
  display: block;
  background-color: #155f65;
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

const SearchContainer = styled.li`
  order: -1;

  a {
    display: block;
    position: relative;
    height: auto;
    background-color: #E9F7F8;
    color: #155F65;
    border: 1px solid #6BB6BC;
    border-radius: 32px;
    padding: 8px 18px 10px;
    width: fit-content;
    margin: 0px auto 38px auto;

    &:focus, &:hover, &:active {
      box-shadow: none;
      color: #155F65;
      background-color: #92D7DD;
      outline: none;
      border-radius: 999px;
    }
  }


  @media (min-width: 992px) {
    order: unset;

    svg {
      margin-right: 10px;
      margin-top: 3px;
    }

    a {
      display: block;
      position: relative;
      height: auto;
      background-color: #E9F7F8;
      color: #155F65;
      border: 1px solid #6BB6BC;
      border-radius: 32px;
      padding: 8px 18px 10px;

      &:focus, &:hover, &:active {
        box-shadow: none;
        color: #155F65;
        background-color: #6BB6BC;
        outline: none;
        border-radius: 999px;
      }
    }

  }
`

export const NewsroomMenu = ({}) => {
  useWebComponentEvents(islandName)

  const [menuItems, setMenuItems] = useState<MenuContentItem[]>([]);
  const [menuOpen, setMenuOpen] = useState<boolean>(false);
  const buttonRef = useRef<HTMLButtonElement>(null);

  useEffect(() => {
    fetch(DRUPAL_DOMAIN + '/jsonapi/menu_items/newsroom')
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
  const existingMenu = document.getElementsByClassName('menu');
  if (existingMenu.length > 0) {
    existingMenu[0].remove();
  }

  return (
    <Nav>
      <Logo open={menuOpen}
      />

      <MobileMenuHeading open={menuOpen}>
        News & Research
      </MobileMenuHeading>

      <MobileMenuButton ref={buttonRef} onClick={() => setMenuOpen(!menuOpen)} aria-expanded={menuOpen}>
        {menuOpen ? <Close /> : <Hamburger />}
        {menuOpen ? "Close" : ""}
      </MobileMenuButton>


      <TopList open={menuOpen}>
        {menuTree.items.map(item => <MenuItem key={item.id} {...item} />)}
        <MobileMenuWrapper></MobileMenuWrapper>
        <SearchContainer>
          <a href="/search">
            <MagnifyingGlass style={{ width: "18px", height: "18px", margin: "5px 15px 0 0"}} />
            Search news & research
          </a>
        </SearchContainer>
      </TopList>
    </Nav>
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
  color: #ffffff;
  background: transparent;
  border: none;
  padding: 0;
  margin: 0 10px 0 -4px;
  box-shadow: none;
  flex-shrink: 0;
  border-radius: 0;
  transition: color 0.2s ease-in-out, background 0.2s ease-in-out, border 0.2s ease-in-out;
  width: 38px;
  height: 38px;

  &:focus, &:hover, &:active {
    box-shadow: none;
    border-bottom: none;
    border-radius: 38px;
    color: #155F65;
    background-color: #92D7DD;
    outline: none;
  }

  @media (min-width: 992px) {
    background: transparent;
    border-radius: unset;
    color: #155f65;
    margin: 4px 10px 0 -4px;

    &:hover, &:focus, &:active {
      border-bottom: 1px solid ${props => props.open ? "#155f65" : "#155f65"};
      background: transparent;
      border-radius: unset;
      color: ${props => props.open ? "#155f65" : "#155f65"};
    }
  }
`

const MenuItemContainer = styled.div<{ open?: boolean, level?: number }>`
  background-color: ${props => props.open ? "#017e7c" : "transparent"};
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-right: ${props => props.level === 0 ? "32px" : "0"};
  width: 100%;

  @media (min-width: 992px) {
    color: ${props => props.open ? "#ffffff" : "#155f65"};
    background-color: ${props => props.open ? "#155f65" : "transparent"};
    padding-bottom: ${props => props.level === 0 ? "46px" : "0"};
    width: ${props => props.level === 0 ? "fit-content" : "100%"};
  }
`

const MenuLink = styled.a<{ isCurrent?: boolean, inTrail?: boolean, level?: number }>`
  color: #ffffff;
  font-weight: 400;
  text-decoration: none;
  padding: ${({level}) => level != 0 ? "16px 0 16px 36px" : "16px 20px"};

  transition: all 0.2s ease-in-out;
  width: 100%;

  &:hover, &:focus {
    text-decoration: underline;
    color: #ffffff;
    background-color: #155F65;
  }

  &:active {
    text-decoration: underline;
    color: #ffffff;
    background-color: #155F65;
  }

  @media (min-width: 992px) {
    color: ${({level}) => level != 0 ? "#ffffff" : "#2E2D29"};
    padding: ${({level}) => level != 0 ? "16px 0 16px 16px" : "16px 0"};
    margin-bottom: ${({level, inTrail, isCurrent}) => level === 0 ? (isCurrent ? "-6px" : (inTrail ? "-6px" : "-6px")) : ""};

    &:hover, &:focus {
      color: ${({level}) => level != 0 ? "#92D7DD" : "#155F65"};
      background-color: transparent;
    }
  }
`

const NoLink = styled.span<{  open?: boolean, level?: number }>`
  color: white;
  font-weight: 600;
  text-decoration: none;
  padding: 16px 0 16px 16px;

  @media (min-width: 992px) {
    color: ${props => props.open ? "#ffffff" : "#155F65"};
    padding: ${({level}) => level != 0 ? "16px 0 16px 16px" : "16px 0"};
  }
`

const MenuList = styled.ul<{ open?: boolean, level?: number }>`
  display: ${props => props.open ? "block" : "none"};
  z-index: ${props => props.level + 1};
  list-style: none;
  padding: 19px 0 15px 0;
  margin: 0;
  min-width: 300px;
  background: #017E7C;

  @media (min-width: 992px) {
    display: ${props => props.open ? "grid" : "none"};
    box-shadow: ${props => props.level === 0 ? "0 10px 20px rgba(0,0,0,.15),0 6px 6px rgba(0,0,0,.2)" : ""};
    position: ${props => props.level === 0 ? "absolute" : "relative"};
    background: #155F65;
    width: 100%;
    left: 0;
    grid-auto-flow: column;
    row-gap: 3.6rem;
    column-gap: 6.6rem;
    color: $sdss-color-white;
    padding: 3.6rem 100px 5.8rem 100px;
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
    left: 0;
    width: 100%;
    z-index: 220;
  }
`

const ListItem = styled.li<{ level?: number }>`
  position: unset;
  border-bottom: ${props => props.level > 0 ? "1px solid transparent" : "1px solid #6BB6BC"};
  margin: 0;

  &:last-child {
    border-bottom: none;
  }

  @media (min-width: 992px) {
    border-bottom: ${props => props.level === 0 ? "none" : "none"};
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

        {items &&
          <>
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
          </>
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


const island = createIslandWebComponent(islandName, NewsroomMenu)
island.render({
  selector: `[data-island="${islandName}"]`,
})
