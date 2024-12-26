<header class="bg-gradient-to-r from-blue-600 via-blue-500 to-blue-400 text-black py-8">
    <div class="container mx-auto px-6">
        <h1 class="text-4xl font-bold mb-4">🔍 جستجو براساس ویژگی‌های پروژه و قطعات</h1>
        <p class="text-lg mb-6">ویژگی مورد نظر خود را انتخاب کرده و جستجو کنید.</p>

        <!-- Search Form -->
        <form action="" method="GET" class="bg-white rounded-lg shadow-lg p-6 border border-gray-200">
            <label for="field" class="block text-gray-700 font-semibold mb-2">Search by:</label>
            <div class="grid grid-cols-1 sm:grid-cols-12 gap-4">
                <!-- Search by Title -->
                <div class="col-span-12 sm:col-span-4">
                    <label for="title" class="block text-gray-700">Project Title</label>
                    <input type="text" id="title" name="title" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                </div>

                <!-- Search by Dimensions -->
                <div class="col-span-12 sm:col-span-4">
                    <label for="dimensions" class="block text-gray-700">Dimensions</label>
                    <select id="dimensions" name="dimensions" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                        <option value="">Select Dimension</option>
                        <?php foreach ($projects as $project): ?>
                            <?php if (!empty($project['dimensions'])): ?>
                                <option value="<?= htmlspecialchars($project['dimensions']) ?>">
                                    <?= htmlspecialchars($project['dimensions']) ?>
                                </option>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Multi-Select: Parts -->
                <div class="col-span-12 sm:col-span-4">
                    <label for="parts" class="block text-gray-700">Parts</label>
                    <select id="parts" name="parts[]" class="w-full border border-gray-300 rounded-lg px-3 py-2" multiple>
                        <?php foreach ($projects as $project): ?>
                            <?php if (!empty($project['parts'])): ?>
                                <?php foreach ($project['parts'] as $part): ?>
                                    <option value="<?= htmlspecialchars($part) ?>">
                                        <?= htmlspecialchars($part) ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Submit Button -->
                <div class="col-span-12 sm:col-span-2">
                    <button type="submit" class="w-full bg-blue-500 text-white px-3 py-2 rounded-lg hover:bg-blue-600">
                        Search
                    </button>
                </div>
            </div>
        </form>
    </div>
</header>
