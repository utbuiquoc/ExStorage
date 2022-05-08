<head>
	<link rel="stylesheet" type="text/css" href="css/layouts/sidebar.css">
</head>

<div class="sidebar d-flex">
	<ul class="sidebar__app d-flex flex-column p-0">
		<ul class="sidebar-items app__docs">
			<li id="file-btn" class="item">
				<a class="item-link" href="library"><i class="item__icon bi bi-file-earmark"></i>
				<div class="file-btn--description">
					<span>Thư viện</span>
				</div></a>
			</li>

			<li id="folder-btn" class="item">
				<a class="item-link" href="exercise"><i class="item__icon bi bi-book"></i>
				<div class="file-btn--description">
					<span>Quản lí file</span>
				</div></a>
			</li>
		</ul>
		<hr class="bar"/>
		<ul class="sidebar-items app__social">
			<li id="friend-btn" class="item">
				<a class="item-link" href="friend"><i class="item__icon bi bi-people"></i>
				<div class="file-btn--description">
					<span>Bạn bè</span>
				</div></a>
			</li>

			<li id="group-btn" class="item">
				<a class="item-link" href="group"><svg class="item__icon" width="30px" height="30px" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 100.353 100.353" style="enable-background:new 0 0 100.353 100.353;" xml:space="preserve">
				<g>
					<path fill="white" d="M49.106,50.437c-12.167,0-22.066,9.898-22.066,22.065c0,0.828,0.671,1.5,1.5,1.5h41.131c0.828,0,1.5-0.672,1.5-1.5   C71.171,60.335,61.272,50.437,49.106,50.437z M30.099,71.002c0.768-9.814,9-17.565,19.007-17.565   c10.007,0,18.239,7.751,19.006,17.565H30.099z"/>
					<path fill="white" d="M48.746,48.456c7.143,0,12.954-5.811,12.954-12.954c0-7.143-5.812-12.954-12.954-12.954   c-7.143,0-12.954,5.811-12.954,12.954C35.792,42.645,41.603,48.456,48.746,48.456z M48.746,25.548c5.488,0,9.954,4.465,9.954,9.954   c0,5.488-4.466,9.954-9.954,9.954c-5.489,0-9.954-4.465-9.954-9.954C38.792,30.013,43.257,25.548,48.746,25.548z"/>
					<path fill="white" d="M19.78,58.714c2.461,0,4.878,0.656,6.99,1.898c0.714,0.422,1.634,0.181,2.053-0.532c0.42-0.714,0.182-1.634-0.533-2.054   c-2.572-1.513-5.515-2.312-8.51-2.312c-9.257,0-16.788,7.531-16.788,16.788c0,0.828,0.671,1.5,1.5,1.5h19.012   c0.829,0,1.5-0.672,1.5-1.5s-0.671-1.5-1.5-1.5H6.073C6.823,64.102,12.684,58.714,19.78,58.714z"/>
					<path fill="white" d="M19.514,53.319c5.521,0,10.014-4.492,10.014-10.014c0-5.522-4.492-10.014-10.014-10.014   c-5.522,0-10.014,4.492-10.014,10.014C9.5,48.826,13.992,53.319,19.514,53.319z M19.514,36.291c3.867,0,7.014,3.146,7.014,7.014   c0,3.867-3.146,7.014-7.014,7.014c-3.868,0-7.014-3.146-7.014-7.014C12.5,39.437,15.646,36.291,19.514,36.291z"/>
					<path fill="white" d="M78.553,55.714c-2.994,0-5.937,0.8-8.51,2.312c-0.715,0.42-0.953,1.339-0.533,2.053c0.42,0.716,1.342,0.953,2.053,0.533   c2.113-1.242,4.53-1.898,6.99-1.898c7.096,0,12.957,5.388,13.707,12.288H74.832c-0.828,0-1.5,0.672-1.5,1.5s0.672,1.5,1.5,1.5   h19.009c0.828,0,1.5-0.672,1.5-1.5C95.341,63.245,87.81,55.714,78.553,55.714z"/>
					<path fill="white" d="M78.82,53.319c5.521,0,10.014-4.492,10.014-10.014c0-5.522-4.492-10.014-10.014-10.014   c-5.522,0-10.015,4.492-10.015,10.014C68.806,48.826,73.298,53.319,78.82,53.319z M78.82,36.291c3.867,0,7.014,3.146,7.014,7.014   c0,3.867-3.146,7.014-7.014,7.014c-3.868,0-7.015-3.146-7.015-7.014C71.806,39.437,74.952,36.291,78.82,36.291z"/>
				</g>
				</svg>
				<div class="file-btn--description">
					<span>Nhóm</span>
				</div></a>
			</li>
		</ul>				
	</ul>

	<ul class="sidebar__user d-flex flex-column p-0">
		<ul class="sidebar-items user__option">
			<li id="home-btn" class="item">
				<a class="item-link" href="/"><i class="item__icon bi bi-house-door"></i>
				<div class="file-btn--description">
					<span>Trang chủ</span>
				</div></a>
			</li>
		</ul>
		<hr class="bar"/>

		<ul class="sidebar-items user__option" <?php if (!Auth::check()) { ?> style='display: none' <?php } ?>>
			<li id="logout-btn" class="item">
				<a class="item-link" href="logout"><svg class="item__icon" width="30px" height="30px" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 50 50">
				    <path fill="white" style="text-indent:0;text-align:start;line-height:normal;text-transform:none;block-progression:tb;-inkscape-font-specification:Sans" d="M 24.90625 0.96875 A 1.0001 1.0001 0 0 0 24.78125 1 A 1.0001 1.0001 0 0 0 24.15625 1.4375 L 16.28125 9.28125 A 1.016466 1.016466 0 1 0 17.71875 10.71875 L 24 4.4375 L 24 26 A 1.0001 1.0001 0 1 0 26 26 L 26 4.4375 L 32.28125 10.71875 A 1.016466 1.016466 0 1 0 33.71875 9.28125 L 25.84375 1.4375 A 1.0001 1.0001 0 0 0 25.375 1.0625 A 1.0001 1.0001 0 0 0 25.1875 1 A 1.0001 1.0001 0 0 0 24.90625 0.96875 z M 38.875 5.71875 A 1.0001 1.0001 0 0 0 38.40625 7.59375 C 43.636229 11.609302 47 17.892396 47 25 C 47 37.162603 37.162603 47 25 47 C 12.837397 47 3 37.162603 3 25 C 3 17.892396 6.3637708 11.609302 11.59375 7.59375 A 1.0001 1.0001 0 0 0 11 5.78125 A 1.0001 1.0001 0 0 0 10.40625 6 C 4.7062292 10.376448 1 17.257604 1 25 C 1 38.243397 11.756603 49 25 49 C 38.243397 49 49 38.243397 49 25 C 49 17.257604 45.293771 10.376448 39.59375 6 A 1.0001 1.0001 0 0 0 38.875 5.71875 z" overflow="visible" font-family="Sans"/>
				</svg>
				<div class="file-btn--description">
					<span>Đăng xuất</span>
				</div></a>
			</li>
		</ul>

		<ul class="sidebar-items user__controll" <?php if (Auth::check()) { ?> style='display: none' <?php } ?>>
			<li id="login-btn" class="item">
				<a class="item-link" href="sign-in"><svg class="item__icon" width="30px" height="30px" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 50 50">
				    <path fill="white" style="text-indent:0;text-align:start;line-height:normal;text-transform:none;block-progression:tb;-inkscape-font-specification:Sans" d="M 25 1 C 14.693688 1 5.9250828 7.5214964 2.53125 16.65625 A 1.0001 1.0001 0 1 0 4.40625 17.34375 C 7.5164172 8.9725036 15.536312 3 25 3 C 37.162603 3 47 12.837397 47 25 C 47 37.162603 37.162603 47 25 47 C 15.536312 47 7.5164172 41.027496 4.40625 32.65625 A 1.0001 1.0001 0 1 0 2.53125 33.34375 C 5.9250828 42.478504 14.693688 49 25 49 C 38.243397 49 49 38.243397 49 25 C 49 11.756603 38.243397 1 25 1 z M 25.90625 15.96875 A 1.0001 1.0001 0 0 0 25.78125 16 A 1.0001 1.0001 0 0 0 25.28125 17.71875 L 31.5625 24 L 2 24 A 1.0001 1.0001 0 0 0 1.90625 24 A 1.001098 1.001098 0 0 0 2 26 L 31.5625 26 L 25.28125 32.28125 A 1.016466 1.016466 0 1 0 26.71875 33.71875 L 34.71875 25.71875 A 1.0001 1.0001 0 0 0 34.71875 24.28125 L 26.71875 16.28125 A 1.0001 1.0001 0 0 0 25.90625 15.96875 z" overflow="visible" font-family="Sans"/>
				</svg>
				<div class="file-btn--description">
					<span>Đăng nhập</span>
				</div></a>
			</li>
		</ul>
		
	</ul>
</div>