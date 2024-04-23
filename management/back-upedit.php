<div class="form-group my-2">
    <label for="parentQuestion">Question: </label>
    <textarea type="text" style="resize: none" class="form-control" rows="3" id="parentQuestion" name="parentQuestion"><?php echo $pqContent; ?></textarea>                                                
</div>
<div class="form-group my-2">
    <div class="row">
        <div class="col">
            <label for="answers">Answers:</label>
            <?php
                $answersWithProducts = [];
                $answersArray = explode('|', $answerContents);
                $associatedProducts = explode(',', $prodNames);

                foreach ($answersArray as $index => $answer) {
                    $product = isset($associatedProducts[$index]) ? $associatedProducts[$index] : 'No associated product';
                    $answersWithProducts[$answer][] = $product;
                }

                foreach ($answersWithProducts as $answer => $products) {?>
                    <input type="text" class="form-control" value="<?php echo $answer;?>" name="answer">
            <?php } ?>
        </div>
        <div class="col">
            <label for="answerProduct">Associated Products:</label>
            <?php foreach ($answersWithProducts as $answer => $products) { ?>
                <select class="form-control answerProducts" name="answerProducts[]" multiple>
                    <?php
                        $productsQuery = "SELECT * FROM product WHERE categoryID = $categoryID";
                        $productsResult = mysqli_query($conn, $productsQuery);

                        while ($productRow = mysqli_fetch_assoc($productsResult)) {
                            $productID   = $productRow['prodID'];
                            $productName = $productRow['prodName'];

                            $selected = in_array($productName, $products) ? 'selected' : '';

                            echo '<option value="' . htmlspecialchars($productID) . '" ' . $selected . '>' . htmlspecialchars($productName) . '</option>';
                        }
                    ?>
                </select>
                <br>
            <?php } ?>
        </div>
    </div>
</div>