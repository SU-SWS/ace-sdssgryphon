const MenuLevel0 = ({...props}) => {
    return (

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

    )
  }
  export default MenuLevel0;
