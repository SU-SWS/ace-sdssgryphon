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

const islandName = 'newsroom-menu-island'

const TopList = styled.ul<{ open?: boolean }>`
  display: ${props => props.open ? "block" : "none"};
  background: #155F65;
  position: absolute;
  top: 0;
  left: 0;
  flex-wrap: wrap;
  justify-content: flex-end;
  list-style: none;
  margin: 0;
  margin-left: calc(50% - 50vw);
  margin-right: calc(50% - 50vw);
  padding: 24px 0;
  font-size: 18px;
  z-index: 1;
  width: 100%;

  @media (min-width: 992px) {
    display: flex;
    background: transparent;
    padding: 0;
    position: unset;
    font-size: 18px;
    width: 100%;
    margin: 0 auto;
  }
`

const MobileMenuButton = styled.button`
  position: absolute;
  top: -60px;
  right: 33%;
  box-shadow: none;
  background: transparent;
  border: 0;
  border-bottom: 2px solid transparent;
  color: #2e2d29;
  padding: 0;
  display: flex;
  flex-direction: column;
  align-items: center;
  font-size: 1.7rem;
  width: 25rem;

  &:hover, &:focus {
    background: transparent;
    color: #2e2d29;
    box-shadow: none;
  }

  @media (min-width: 992px) {
    display: none;
  }
`

const SearchContainer = styled.li`
  padding: 0;
  margin: 0 0 20px;

  form {
    display: flex;
    flex-direction: column;
    gap: 10px;
  }

  label {
    padding: 0 10px;
    margin: 0;
    color: #ffffff;
  }

  input {
    color: #ffffff;
    margin: 0 auto;
    width: 50%;
    border-radius: 999px;
    height: 40px;
    padding: 0 20px;
    max-width: 100%;
    background: transparent;
  }

  button {
    position: absolute;
    top: 0;
    right: 25%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    background: transparent;
    color: #ffffff;
    border: 1px solid D5D5D4;
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
      <MobileMenuButton ref={buttonRef} onClick={() => setMenuOpen(!menuOpen)} aria-expanded={menuOpen}>
        {menuOpen ? <Close/> : <Hamburger/>}
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
                placeholder="Search news & research"
                name="key"
              />
              <button type="submit">
                <MagnifyingGlass style={{width: "25px", height: "25px"}}/>
                <span className="visually-hidden">Submit Search</span>
              </button>
            </div>
          </form>

        </SearchContainer>
        {menuTree.items.map(item => <MenuItem key={item.id} {...item}/>)}
      </TopList>
    </Nav>
  )
}

const Nav = styled.nav`
  position: relative;
  width: 100%;

  @media (min-width: 992px) {
    position: unset;
  }
`

const Button = styled.button`
  color: #ffffff;
  background: #155F65;
  border: none;
  border-bottom: 1px solid transparent;
  padding: 0;
  margin: 0 0 -4px;
  box-shadow: none;
  flex-shrink: 0;
  border-radius: 999px;
  transition: color 0.2s ease-in-out, background 0.2s ease-in-out, border 0.2s ease-in-out;
  width: 38px;
  height: 38px;

  &:hover, &:focus {
    box-shadow: none;
    border-bottom: 1px solid #b1040e;
    background: #f4f4f4;
    color: #2E2D29;
  }

  @media (min-width: 992px) {
    color: #155F65;
    background: transparent;
    border-radius: 0;

    &:hover, &:focus {
      border-bottom: 1px solid #2e2d29;
      color: ##2E2D29;
      background: transparent;
    }
  }
`

const MenuItemContainer = styled.div<{ level?: number }>`
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-right: ${props => props.level === 0 ? "32px" : "0"};

  width: 100%;

  @media (min-width: 992px) {
    width: ${props => props.level === 0 ? "fit-content" : "100%"};
    margin-bottom: ${props => props.level === 0 ? "6px" : ""};
  }
`

const MenuLink = styled.a<{ isCurrent?: boolean, inTrail?: boolean, level?: number }>`
  color: #ffffff;
  font-weight: 400;
  text-decoration: none;
  padding: 16px 0 16px 20px;
  transition: all 0.2s ease-in-out;
  width: 100%;

  &:hover, &:focus {
    text-decoration: underline;
    color: #ffffff;
  }

  @media (min-width: 992px) {
    color: ${({level}) => level != 0 ? "#ffffff" : "#2E2D29"};
    padding: ${({level}) => level != 0 ? "16px 0 16px 16px" : "16px 0"};
    border-bottom: ${({level, inTrail, isCurrent}) => level === 0 ? (isCurrent ? "6px solid #2e2d29" : (inTrail ? "6px solid #b6b1a9" : "6px solid transparent")) : ""};
    margin-bottom: ${({level, inTrail, isCurrent}) => level === 0 ? (isCurrent ? "-6px" : (inTrail ? "-6px" : "-6px")) : ""};

    &:hover, &:focus {
      color: ${({level}) => level != 0 ? "#92D7DD" : "#155F65"};
    }
  }
`

const NoLink = styled.span<{ level?: number }>`
  color: #ffffff;
  font-weight: 600;
  text-decoration: none;
  padding: 16px 0 16px 16px;

  @media (min-width: 992px) {
    color: #155F65;
    padding: ${({level}) => level != 0 ? "16px 0 16px 16px" : "16px 0"};
  }
`

const MenuList = styled.ul<{ open?: boolean, level?: number }>`
  display: ${props => props.open ? "block" : "none"};
  z-index: ${props => props.level + 1};
  list-style: none;
  padding: 0;
  margin: 0;
  border-top: 1px solid transparent;
  min-width: 300px;
  background: #017E7C;

  @media (min-width: 992px) {
    display: ${props => props.open ? "flex" : "none"};
    box-shadow: ${props => props.level === 0 ? "0 10px 20px rgba(0,0,0,.15),0 6px 6px rgba(0,0,0,.2)" : ""};
    position: ${props => props.level === 0 ? "absolute" : "relative"};
    background: #155F65;
    border-top: 1px solid #d9d9d9;
    width: 100%;
    left: 0;
    flex-wrap: wrap;
    align-items: center;
    justify-content: space-evenly;
    align-content: space-around;
    row-gap: 4rem;
    column-gap: 7.5rem;
    margin-top: 4.4rem;
    color: $sdss-color-white;
    padding: 4.4rem;
  }
`

const MenuListWrapper = styled.div<{ open?: boolean, level?: number }>`

  background-color: lime;

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
    background-color: orange;
  }
`

const ListItem = styled.li<{ level?: number }>`
  position: unset;
  border-bottom: ${props => props.level > 0 ? "1px solid transparent" : "1px solid #6BB6BC"};
  padding: ${props => props.level > 0 ? "0 0 0 16px" : "0"};
  margin: 0;

  &:last-child {
    border-bottom: none;
  }

  @media (min-width: 992px) {
    border-bottom: ${props => props.level === 0 ? "none" : "none"};
    padding: ${props => props.level > 0 ? "0 10px" : "0"};
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
      onOutsideFocus={() => setSubmenuOpen(false)}
      component={ListItem}
      level={level}
    >

      <MenuItemContainer level={level}>
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
          <NoLink>{title}</NoLink>
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
