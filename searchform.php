<form role="search" method="get" class="search-form" action="<?php echo esc_url(home_url('/')); ?>" style="max-width: 500px; margin: 0 auto;">
    <div style="display: flex; gap: 10px;">
        <input 
            type="search" 
            class="search-field" 
            placeholder="검색어를 입력하세요..." 
            value="<?php echo get_search_query(); ?>" 
            name="s" 
            style="flex: 1; padding: 12px 20px; border: 2px solid #e0e0e0; border-radius: 25px; font-size: 16px; outline: none; transition: all 0.3s;"
            onfocus="this.style.borderColor='#667eea'"
            onblur="this.style.borderColor='#e0e0e0'"
        />
        <button 
            type="submit" 
            class="search-submit" 
            style="padding: 12px 30px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: #fff; border: none; border-radius: 25px; font-weight: 600; cursor: pointer; transition: all 0.3s;"
            onmouseover="this.style.transform='scale(1.05)'"
            onmouseout="this.style.transform='scale(1)'"
        >
            검색
        </button>
    </div>
</form>
