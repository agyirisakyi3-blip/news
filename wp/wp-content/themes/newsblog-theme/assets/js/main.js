(function() {
  'use strict';

  const menuToggle = document.getElementById('menu-toggle');
  const primaryMenu = document.getElementById('primary-menu');

  if (menuToggle && primaryMenu) {
    menuToggle.addEventListener('click', function() {
      this.classList.toggle('active');
      primaryMenu.classList.toggle('toggled');
    });
  }

  const searchToggle = document.getElementById('search-toggle');
  const searchBar = document.getElementById('search-bar');

  if (searchToggle && searchBar) {
    searchToggle.addEventListener('click', function(e) {
      e.preventDefault();
      if (searchBar.style.display === 'none' || searchBar.style.display === '') {
        searchBar.style.display = 'block';
        searchBar.querySelector('input').focus();
      } else {
        searchBar.style.display = 'none';
      }
    });
  }

  const tickerTrack = document.getElementById('ticker-track');
  if (tickerTrack) {
    const items = tickerTrack.querySelectorAll('.ticker-item');
    if (items.length > 1) {
      tickerTrack.innerHTML = tickerTrack.innerHTML + tickerTrack.innerHTML;
    }
  }

  const submenuParents = document.querySelectorAll('.main-navigation ul li.menu-item-has-children');
  if (window.innerWidth <= 768) {
    submenuParents.forEach(function(parent) {
      const link = parent.querySelector('a');
      const submenu = parent.querySelector('ul');
      if (link && submenu) {
        link.addEventListener('click', function(e) {
          if (submenu.classList.contains('toggled')) {
            return true;
          }
          e.preventDefault();
          submenu.classList.toggle('toggled');
        });
      }
    });
  }

  function setDarkMode(enabled) {
    const body = document.body;
    if (enabled) {
      body.classList.add('dark-mode');
      document.cookie = 'dark_mode=1; path=/; max-age=' + (365 * 24 * 60 * 60);
    } else {
      body.classList.remove('dark-mode');
      document.cookie = 'dark_mode=0; path=/; max-age=' + (365 * 24 * 60 * 60);
    }
  }

  const darkToggle = document.getElementById('dark-mode-toggle');
  if (darkToggle) {
    if (document.body.classList.contains('dark-mode')) {
      darkToggle.querySelector('.sun-icon').style.display = 'none';
      darkToggle.querySelector('.moon-icon').style.display = 'block';
    }
    darkToggle.addEventListener('click', function() {
      const isDark = !document.body.classList.contains('dark-mode');
      setDarkMode(isDark);
      this.querySelector('.sun-icon').style.display = isDark ? 'none' : 'block';
      this.querySelector('.moon-icon').style.display = isDark ? 'block' : 'none';
    });
  }

  const loadMoreBtn = document.getElementById('load-more-btn');
  const loadMoreWrap = document.querySelector('.load-more-wrap');

  if (loadMoreBtn && loadMoreWrap) {
    const maxPages = parseInt(loadMoreWrap.dataset.max) || 1;
    let currentPage = parseInt(loadMoreWrap.dataset.page) || 1;
    let loading = false;

    function createSpinner() {
      const spinner = document.createElement('div');
      spinner.className = 'loading-spinner';
      spinner.id = 'loading-spinner';
      loadMoreWrap.appendChild(spinner);
      return spinner;
    }

    loadMoreBtn.addEventListener('click', function() {
      if (loading || currentPage >= maxPages) return;
      loading = true;
      loadMoreBtn.classList.add('loading');
      loadMoreBtn.textContent = 'Loading...';

      const spinner = document.getElementById('loading-spinner') || createSpinner();
      spinner.classList.add('active');

      currentPage++;
      const query = loadMoreWrap.dataset.query;
      const formData = new FormData();
      formData.append('action', 'newsblog_load_more');
      formData.append('page', currentPage);
      formData.append('query', query);

      fetch(newsblog_ajax.url, {
        method: 'POST',
        body: formData,
      })
      .then(function(response) { return response.text(); })
      .then(function(html) {
        const grid = document.getElementById('posts-grid');
        if (grid) {
          grid.insertAdjacentHTML('beforeend', html);
        }
        loadMoreBtn.classList.remove('loading');
        loadMoreBtn.textContent = 'Load More';
        spinner.classList.remove('active');
        loading = false;

        if (currentPage >= maxPages) {
          loadMoreBtn.style.display = 'none';
        }
      })
      .catch(function() {
        loadMoreBtn.classList.remove('loading');
        loadMoreBtn.textContent = 'Load More';
        spinner.classList.remove('active');
        loading = false;
      });
    });
  }
})();
