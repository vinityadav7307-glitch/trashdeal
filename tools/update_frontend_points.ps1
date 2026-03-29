$path = 'C:\Users\VINIT YADAV\Downloads\trashdeal-website.html'
$content = Get-Content -Raw -Encoding UTF8 $path
$backup = 'C:\Users\VINIT YADAV\Downloads\trashdeal-website.backup.html'

Set-Content -Path $backup -Value $content -Encoding UTF8

$content = $content.Replace('<title>TrashDeal – Turn Your Waste Into Value</title>', '<title>TrashDeal - Turn Your Waste Into Points</title>')
$content = $content.Replace('>Turn Your Waste<br>Into <span>Real Value</span><', '>Turn Your Waste<br>Into <span>Real Points</span><')
$content = $content.Replace('<div class="hero-stat-val">₹257</div>', '<div class="hero-stat-val">1200+</div>')
$content = $content.Replace('<div class="hero-stat-lbl">Avg Monthly Value</div>', '<div class="hero-stat-lbl">Avg Monthly Points</div>')
$content = $content.Replace('From your doorstep to real cash value — TrashDeal makes responsible waste disposal effortless and rewarding.', 'From your doorstep to verified rewards, TrashDeal makes responsible waste disposal effortless, trackable, and fun.')
$content = $content.Replace('Based on actual waste value — our points reflect real market rates for each material type.', 'Points are earned only by waste type, so the system stays simple, transparent, and easy to understand.')
$content = $content.Replace('Average Indian Household Monthly Value', 'Average Monthly Points')
$content = $content.Replace('₹257', '1200+')
$content = $content.Replace('22kg organic + 10.5kg recyclable + 5kg inert', 'Built from regular organic and recyclable pickups')
$content = $content.Replace('Your points convert to actual money value. Spend on groceries, vouchers, eco donations, and more.', 'Redeem points for useful rewards, green perks, and community impact - no currency conversion needed.')
$content = $content.Replace('Redeem points for useful rewards, green perks, and community impact - no cash conversion needed.', 'Redeem points for useful rewards, green perks, and community impact - no currency conversion needed.')
$content = $content.Replace('Real tree planted', 'Eco reward')
$content = $content.Replace('Premium Month', 'Green Streak Booster')
$content = $content.Replace('Talk Time ₹50', 'Mobile Recharge Pack')
$content = $content.Replace('Average household waste value per month — going unrealised without TrashDeal', 'Average household reward potential per month with regular sorting')
$content = $content.Replace('Of mixed waste value is lost — segregation at source recovers it', 'Of mixed waste points are missed when waste is not sorted at source')
$content = $content.Replace('Start turning <span>waste into value</span>', 'Start turning <span>waste into points</span>')
$content = $content.Replace('Turn your waste into real value. Aligned with Swachh Bharat Mission. Building a cleaner India — one pickup at a time.', 'Turn your waste into meaningful points. Aligned with Swachh Bharat Mission. Building a cleaner India - one pickup at a time.')
$content = $content.Replace('30 pts = ₹6 · Earn by doing good', 'Earn points by doing good')
$content = $content.Replace('<div><div class="ddc-badge-main">30 pts = ₹6</div><div class="ddc-badge-sub">Base conversion rate</div></div>', '<div><div class="ddc-badge-main">Points verified at pickup</div><div class="ddc-badge-sub">Rewards are based on waste type only</div></div>')
$content = $content.Replace('<a href="#premium">Premium</a>', '')
$content = $content.Replace('<a href="#premium" onclick="toggleMenu()">Premium</a>', '')

$tableReplacement = @'
<tr><th>Waste Type</th><th>Points per kg</th></tr>
            </thead>
            <tbody>
              <tr>
                <td>Organic</td>
                <td class="pts-val">30 pts/kg</td>
              </tr>
              <tr>
                <td>Recyclable</td>
                <td class="pts-val">60 pts/kg</td>
              </tr>
              <tr>
                <td>Inert</td>
                <td style="color:rgba(255,255,255,.3);">0 pts/kg</td>
              </tr>
            </tbody>
'@
$content = [regex]::Replace($content, '<tr><th>Waste Type</th><th>Base Points</th><th>Value/kg</th><th>Avg/month</th></tr>\s*</thead>\s*<tbody>.*?</tbody>', $tableReplacement, 'Singleline')

$milestoneReplacement = @'
<div class="pts-card">
          <div class="pts-card-title">Points Milestones</div>
          <div class="level-bars">
            <div class="lv-bar-row">
              <div class="lv-bar-label" style="color:#cd7f32;">Starter</div>
              <div class="lv-bar-track"><div class="lv-bar-fill" style="width:35%;background:#cd7f32;"></div></div>
              <div class="lv-bar-pts" style="color:#cd7f32;">250 pts</div>
            </div>
            <div class="lv-bar-row">
              <div class="lv-bar-label" style="color:#9E9E9E;">Saver</div>
              <div class="lv-bar-track"><div class="lv-bar-fill" style="width:68%;background:#9E9E9E;"></div></div>
              <div class="lv-bar-pts" style="color:var(--gm);">750 pts</div>
            </div>
            <div class="lv-bar-row">
              <div class="lv-bar-label" style="color:var(--y);">Champion</div>
              <div class="lv-bar-track"><div class="lv-bar-fill" style="width:100%;background:var(--y);"></div></div>
              <div class="lv-bar-pts" style="color:var(--y);">1500 pts</div>
            </div>
          </div>
        </div>
'@
$content = [regex]::Replace($content, '<div class="pts-card">\s*<div class="pts-card-title">Level Conversion Rates</div>.*?</div>\s*</div>', $milestoneReplacement, 'Singleline')

$content = [regex]::Replace($content, '\s*<div class="convert-banner fade-up">.*?</div>\s*</div>', "`r`n", 'Singleline')
$content = [regex]::Replace($content, '\s*<div class="reward-value">.*?</div>', '', 'Singleline')
$content = [regex]::Replace($content, '\s*<section id="premium".*?</section>', "`r`n", 'Singleline')
$content = [regex]::Replace($content, '<div class="convert-banner fade-up">[\s\S]*?</div>\s*<div class="redeem-grid">', '<div class="redeem-grid">', 'Singleline')

$content = $content.Replace('Level Conversion Rates', 'Points Milestones')
$content = $content.Replace('30 pts = ₹6', '250 pts')
$content = $content.Replace('30 pts = ₹7', '750 pts')
$content = $content.Replace('30 pts = ₹8', '1500 pts')
$content = $content.Replace('1700 pts = ₹397', 'Redeem rewards with points')

Set-Content -Path $path -Value $content -Encoding UTF8
